<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationMark;
use App\Models\EvaluationSection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $evaluations = Evaluation::with(['pyd', 'ppp', 'ppk', 'pydGroup'])->get();
        } elseif ($user->isPPP()) {
            $evaluations = Evaluation::where('ppp_id', $user->id)
                ->with(['pyd', 'ppk', 'pydGroup'])
                ->get();
        } elseif ($user->isPPK()) {
            $evaluations = Evaluation::where('ppk_id', $user->id)
                ->with(['pyd', 'ppp', 'pydGroup'])
                ->get();
        } elseif ($user->isPYD()) {
            $evaluations = Evaluation::where('pyd_id', $user->id)
                ->with(['ppp', 'ppk', 'pydGroup'])
                ->get();
        } else {
            $evaluations = [];
        }

        return response()->json($evaluations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pyd_id' => 'required|exists:users,id',
            'ppp_id' => 'required|exists:users,id',
            'ppk_id' => 'required|exists:users,id',
            'pyd_group_id' => 'required|exists:pyd_groups,id',
            'evaluation_period_id' => 'required|exists:evaluation_periods,id',
        ]);
    
        $period = EvaluationPeriod::find($request->evaluation_period_id);
        
        $evaluation = Evaluation::create([
            'pyd_id' => $request->pyd_id,
            'ppp_id' => $request->ppp_id,
            'ppk_id' => $request->ppk_id,
            'pyd_group_id' => $request->pyd_group_id,
            'year' => $period->start_date->year,
            'evaluation_period_id' => $request->evaluation_period_id,
        ]);
    
        // Create initial work target
        $evaluation->workTargets()->create([
            'type' => $period->type === 'mid_year' ? 'pertengahan_tahun' : 'awal_tahun',
        ]);
    
        // Notify PYD
        Notification::create([
            'user_id' => $request->pyd_id,
            'message' => 'Anda telah ditugaskan untuk penilaian prestasi',
            'action_url' => route('evaluations.show', $evaluation->id),
        ]);
    
        return redirect()->route('evaluations.index')
            ->with('success', 'Penilaian berjaya dicipta');
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load([
            'pyd', 'ppp', 'ppk', 'pydGroup', 
            'marks.criteria.section', 
            'workTargets.items'
        ]);

        $sections = EvaluationSection::with('criterias')->get();

        return response()->json([
            'evaluation' => $evaluation,
            'sections' => $sections,
        ]);
    }

    public function create()
    {
        $pydUsers = User::whereHas('userType', fn($q) => $q->where('name', 'PYD'))->get();
        $pppUsers = User::whereHas('userType', fn($q) => $q->where('name', 'PPP'))->get();
        $ppkUsers = User::whereHas('userType', fn($q) => $q->where('name', 'PPK'))->get();
        $pydGroups = PydGroup::all();
        $activePeriod = EvaluationPeriod::active()->first();

        return view('evaluations.create', compact(
            'pydUsers', 
            'pppUsers', 
            'ppkUsers', 
            'pydGroups',
            'activePeriod'
    ));
    }

    public function edit(Evaluation $evaluation)
    {
        $pydUsers = User::whereHas('userType', fn($q) => $q->where('name', 'PYD'))->get();
        $pppUsers = User::whereHas('userType', fn($q) => $q->where('name', 'PPP'))->get();
        $ppkUsers = User::whereHas('userType', fn($q) => $q->where('name', 'PPK'))->get();
        $pydGroups = PydGroup::all();

        return view('evaluations.edit', compact('evaluation', 'pydUsers', 'pppUsers', 'ppkUsers', 'pydGroups'));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:draf,pyd_submit,ppp_submit,ppk_submit,selesai',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $evaluation->update($request->all());

        // Notify relevant users when status changes
        if ($request->has('status')) {
            $this->handleStatusChangeNotifications($evaluation);
        }

        return response()->json([
            'message' => 'Penilaian berjaya dikemaskini',
            'evaluation' => $evaluation,
        ]);
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return response()->json([
            'message' => 'Penilaian berjaya dipadam',
        ]);
    }

    public function submitSection(Request $request, Evaluation $evaluation, $sectionCode)
    {
        $section = EvaluationSection::where('code', $sectionCode)->firstOrFail();
        $user = $request->user();

        // Validate user can submit this section
        $this->validateSectionSubmission($user, $section, $evaluation);

        // Handle section-specific logic
        switch ($sectionCode) {
            case 'bahagian_ii':
                // PYD submits Bahagian II
                $evaluation->update(['status' => 'pyd_submit']);
                $this->createNotification(
                    $evaluation->ppp_id,
                    'PYD telah menghantar Bahagian II untuk penilaian',
                    route('evaluations.show', $evaluation->id)
                );
                break;

            case 'bahagian_viii':
                // PPP submits Bahagian VIII
                $evaluation->update(['status' => 'ppp_submit']);
                $this->createNotification(
                    $evaluation->ppk_id,
                    'PPP telah menghantar penilaian untuk semakan anda',
                    route('evaluations.show', $evaluation->id)
                );
                break;

            case 'bahagian_ix':
                // PPK submits Bahagian IX
                $evaluation->update(['status' => 'selesai']);
                $this->createNotification(
                    $evaluation->pyd_id,
                    'Penilaian prestasi anda telah selesai',
                    route('evaluations.show', $evaluation->id)
                );
                break;
        }

        return response()->json([
            'message' => 'Bahagian berjaya dihantar',
            'evaluation' => $evaluation,
        ]);
    }

    public function saveMarks(Request $request, Evaluation $evaluation)
    {
        $validator = Validator::make($request->all(), [
            'marks' => 'required|array',
            'marks.*.criteria_id' => 'required|exists:evaluation_criterias,id',
            'marks.*.mark' => 'required|integer|min:1|max:10',
            'marks.*.comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = $request->user();
        $isPPP = $user->isPPP();
        $isPPK = $user->isPPK();

        if (!$isPPP && !$isPPK) {
            return response()->json([
                'message' => 'Hanya PPP atau PPK boleh menyimpan markah'
            ], 403);
        }

        foreach ($request->marks as $markData) {
            $mark = EvaluationMark::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'criteria_id' => $markData['criteria_id'],
                [
                    $isPPP ? 'ppp_mark' : 'ppk_mark' => $markData['mark'],
                    $isPPP ? 'ppp_comment' : 'ppk_comment' => $markData['comment'] ?? null,
                ]
                ]
            );

        }

        // Calculate and save total marks for Bahagian VII
        $this->calculateTotalMarks($evaluation);

        return response()->json([
            'message' => 'Markah berjaya disimpan',
            'evaluation' => $evaluation->fresh()->load('marks.criteria.section'),
        ]);
    }

    private function validateSectionSubmission($user, $section, $evaluation)
    {
        if ($section->is_pyd_section && !$user->isPYD()) {
            abort(403, 'Hanya PYD boleh menghantar bahagian ini');
        }

        if ($section->is_ppp_section && !$user->isPPP()) {
            abort(403, 'Hanya PPP boleh menghantar bahagian ini');
        }

        if ($section->is_ppk_section && !$user->isPPK()) {
            abort(403, 'Hanya PPK boleh menghantar bahagian ini');
        }

        // Additional validation based on evaluation status
        if ($section->code === 'bahagian_ii' && $evaluation->status !== 'draf') {
            abort(400, 'Penilaian tidak dalam status draf');
        }

        if ($section->code === 'bahagian_viii' && $evaluation->status !== 'pyd_submit') {
            abort(400, 'Penilaian belum dihantar oleh PYD');
        }

        if ($section->code === 'bahagian_ix' && $evaluation->status !== 'ppp_submit') {
            abort(400, 'Penilaian belum dihantar oleh PPP');
        }
    }

    private function calculateTotalMarks(Evaluation $evaluation)
    {
        $marks = $evaluation->marks;

        $pppTotal = 0;
        $ppkTotal = 0;

        foreach ($marks as $mark) {
            $section = $mark->criteria->section;

            if ($mark->ppp_mark) {
                $pppTotal += ($mark->ppp_mark / $mark->criteria->max_mark) * $section->weightage;
            }

            if ($mark->ppk_mark) {
                $ppkTotal += ($mark->ppk_mark / $mark->criteria->max_mark) * $section->weightage;
            }
        }

        // Update Bahagian VII marks
        EvaluationMark::updateOrCreate(
            [
                'evaluation_id' => $evaluation->id,
                'criteria_id' => EvaluationCriteria::whereHas('section', function($q) {
                    $q->where('code', 'bahagian_vii');
                })->first()->id,
            ],
            [
                'ppp_mark' => $pppTotal,
                'ppk_mark' => $ppkTotal,
            ]
        );
    }

    private function handleStatusChangeNotifications(Evaluation $evaluation)
    {
        switch ($evaluation->status) {
            case 'pyd_submit':
                $this->createNotification(
                    $evaluation->ppp_id,
                    'PYD telah menghantar penilaian untuk semakan anda',
                    route('evaluations.show', $evaluation->id)
                );
                break;

            case 'ppp_submit':
                $this->createNotification(
                    $evaluation->ppk_id,
                    'PPP telah menghantar penilaian untuk semakan anda',
                    route('evaluations.show', $evaluation->id)
                );
                break;

            case 'selesai':
                $this->createNotification(
                    $evaluation->pyd_id,
                    'Penilaian prestasi anda telah selesai',
                    route('evaluations.show', $evaluation->id)
                );
                break;
        }
    }

    private function createNotification($userId, $message, $actionUrl)
    {
        \App\Models\Notification::create([
            'user_id' => $userId,
            'message' => $message,
            'action_url' => $actionUrl,
        ]);
    }
}