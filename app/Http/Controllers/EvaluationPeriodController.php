<?php

namespace App\Http\Controllers;

use App\Models\EvaluationPeriod;
use Illuminate\Http\Request;

class EvaluationPeriodController extends Controller
{
    public function index()
    {
        $periods = EvaluationPeriod::latest()->get();
        return view('evaluation-periods.index', compact('periods'));
    }

    public function create()
    {
        return view('evaluation-periods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'type' => 'required|in:yearly,mid_year,adhoc',
        ]);

        EvaluationPeriod::create($request->all());

        return redirect()->route('evaluation-periods.index')
            ->with('success', 'Tempoh penilaian berjaya dicipta');
    }

    public function edit(EvaluationPeriod $evaluationPeriod)
    {
        return view('evaluation-periods.edit', compact('evaluationPeriod'));
    }

    public function update(Request $request, EvaluationPeriod $evaluationPeriod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'type' => 'required|in:yearly,mid_year,adhoc',
        ]);

        $evaluationPeriod->update($request->all());

        return redirect()->route('evaluation-periods.index')
            ->with('success', 'Tempoh penilaian berjaya dikemaskini');
    }

    public function destroy(EvaluationPeriod $evaluationPeriod)
    {
        $evaluationPeriod->delete();

        return redirect()->route('evaluation-periods.index')
            ->with('success', 'Tempoh penilaian berjaya dipadam');
    }

    public function toggleActive(EvaluationPeriod $evaluationPeriod)
    {
        // Deactivate all other periods first
        EvaluationPeriod::where('id', '!=', $evaluationPeriod->id)
            ->update(['is_active' => false]);

        $evaluationPeriod->update(['is_active' => !$evaluationPeriod->is_active]);

        return back()->with('success', 'Status tempoh penilaian berjaya dikemaskini');
    }

    public function massAssign(EvaluationPeriod $evaluationPeriod)
    {
        $pydUsers = User::whereHas('userType', fn($q) => $q->where('name', 'PYD'))->get();
        $pppUsers = User::whereHas('userType', fn($q) => $q->where('name', 'PPP'))->get();
        $ppkUsers = User::whereHas('userType', fn($q) => $q->where('name', 'PPK'))->get();
        $pydGroups = PydGroup::all();

        return view('evaluation-periods.mass-assign', compact(
            'evaluationPeriod',
            'pydUsers',
            'pppUsers',
            'ppkUsers',
            'pydGroups'
        ));
    }

    public function storeMassAssign(Request $request, EvaluationPeriod $evaluationPeriod)
    {
        $request->validate([
            'assignments' => 'required|array',
            'assignments.*.pyd_id' => 'required|exists:users,id',
            'assignments.*.ppp_id' => 'required|exists:users,id',
            'assignments.*.ppk_id' => 'required|exists:users,id',
            'assignments.*.pyd_group_id' => 'required|exists:pyd_groups,id',
        ]);

        foreach ($request->assignments as $assignment) {
            // Check if evaluation already exists
            $exists = Evaluation::where('pyd_id', $assignment['pyd_id'])
                ->where('evaluation_period_id', $evaluationPeriod->id)
                ->exists();

            if (!$exists) {
                $evaluation = Evaluation::create([
                    'pyd_id' => $assignment['pyd_id'],
                    'ppp_id' => $assignment['ppp_id'],
                    'ppk_id' => $assignment['ppk_id'],
                    'pyd_group_id' => $assignment['pyd_group_id'],
                    'year' => $evaluationPeriod->start_date->year,
                    'evaluation_period_id' => $evaluationPeriod->id,
                    'status' => 'draf',
                ]);

                // Create initial work target
                $evaluation->workTargets()->create([
                    'type' => $evaluationPeriod->type === 'mid_year' ? 'pertengahan_tahun' : 'awal_tahun',
                ]);

                // Notify PYD
                Notification::create([
                    'user_id' => $assignment['pyd_id'],
                    'message' => 'Anda telah ditugaskan untuk penilaian prestasi',
                    'action_url' => route('evaluations.show', $evaluation->id),
                ]);
            }
        }

        return redirect()->route('evaluation-periods.index')
            ->with('success', 'Penilaian berjaya ditugaskan secara pukal');
    }
}