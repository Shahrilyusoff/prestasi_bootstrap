<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\WorkTarget;
use App\Models\WorkTargetItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkTargetController extends Controller
{
    public function index(Evaluation $evaluation)
    {
        $workTargets = $evaluation->workTargets()->with('items')->get();
        return response()->json($workTargets);
    }

    public function store(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:evaluations,id',
            'type' => 'required|in:awal_tahun,pertengahan_tahun,akhir_tahun',
            'items' => 'sometimes|array',
            'items.*.activity' => 'required_with:items|string',
            'items.*.performance_indicator' => 'required_with:items|string',
            'added_items' => 'sometimes|array',
            'added_items.*.activity' => 'required_with:added_items|string',
            'added_items.*.performance_indicator' => 'required_with:added_items|string',
            'added_items.*.is_added' => 'sometimes|boolean',
            'removed_items' => 'sometimes|array',
            'removed_items.*.is_removed' => 'sometimes|boolean',
            'removed_items.*.activity' => 'required_with:removed_items|string',
            'approved' => 'sometimes|boolean',
        ]);
    
        $evaluation = Evaluation::find($request->evaluation_id);
        $user = auth()->user();
    
        // Check if work target exists
        $workTarget = WorkTarget::updateOrCreate(
            [
                'evaluation_id' => $evaluation->id,
                'type' => $request->type
            ],
            [
                'approved' => $request->approved ?? false,
                'pyd_report' => $user->isPYD() ? $request->report : null,
                'ppp_report' => $user->isPPP() ? $request->report : null,
            ]
        );
    
        // Handle items based on type
        if ($request->type === 'awal_tahun') {
            $this->processInitialWorkTarget($workTarget, $request->items);
        } elseif ($request->type === 'pertengahan_tahun') {
            $this->processMidYearReview($workTarget, $request->added_items, $request->removed_items);
        } elseif ($request->type === 'akhir_tahun') {
            $this->processYearEndReport($workTarget, $request);
        }
    
        // Notifications
        $this->sendWorkTargetNotifications($workTarget, $user);
    
        return redirect()->route('evaluations.show', $evaluation->id)
            ->with('success', 'SKT berjaya disimpan');
    }

    public function show(WorkTarget $workTarget)
    {
        $workTarget->load('items', 'evaluation.pyd', 'evaluation.ppp');
        return response()->json($workTarget);
    }

    public function update(Request $request, WorkTarget $workTarget)
    {
        $validator = Validator::make($request->all(), [
            'approved' => 'sometimes|boolean',
            'pyd_report' => 'sometimes|string',
            'ppp_report' => 'sometimes|string',
            'items' => 'sometimes|array',
            'items.*.id' => 'sometimes|exists:work_target_items,id',
            'items.*.activity' => 'sometimes|string',
            'items.*.performance_indicator' => 'sometimes|string',
            'items.*.is_added' => 'sometimes|boolean',
            'items.*.is_removed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = $request->user();

        // Validate user can update this work target
        $this->validateWorkTargetUpdate($workTarget, $user);

        // Update work target
        $workTarget->update($request->only(['approved', 'pyd_report', 'ppp_report']));

        // Update items if provided
        if ($request->has('items')) {
            foreach ($request->items as $itemData) {
                if (isset($itemData['id'])) {
                    $item = WorkTargetItem::find($itemData['id']);
                    $item->update($itemData);
                } else {
                    $workTarget->items()->create($itemData);
                }
            }
        }

        // Notify relevant users
        $this->handleWorkTargetNotifications($workTarget, $user);

        return response()->json([
            'message' => 'Sasaran kerja berjaya dikemaskini',
            'work_target' => $workTarget->fresh()->load('items'),
        ]);
    }

    public function destroy(WorkTarget $workTarget)
    {
        $workTarget->delete();

        return response()->json([
            'message' => 'Sasaran kerja berjaya dipadam',
        ]);
    }

    private function validateWorkTargetSubmission($evaluation, $type, $user)
    {
        // Check if work target already exists for this type
        if ($evaluation->workTargets()->where('type', $type)->exists()) {
            abort(400, 'Sasaran kerja untuk jenis ini sudah wujud');
        }

        // Validate based on type
        switch ($type) {
            case 'awal_tahun':
                if (!$user->isPYD() && !$user->isPPP()) {
                    abort(403, 'Hanya PYD atau PPP boleh menetapkan SKT awal tahun');
                }
                break;

            case 'pertengahan_tahun':
                if (!$user->isPYD()) {
                    abort(403, 'Hanya PYD boleh membuat kajian semula pertengahan tahun');
                }
                // Check if awal tahun exists and is approved
                if (!$evaluation->workTargets()->where('type', 'awal_tahun')->where('approved', true)->exists()) {
                    abort(400, 'Sasaran kerja awal tahun belum diluluskan');
                }
                break;

            case 'akhir_tahun':
                if (!$user->isPYD() && !$user->isPPP()) {
                    abort(403, 'Hanya PYD atau PPP boleh membuat laporan akhir tahun');
                }
                break;
        }
    }

    private function validateWorkTargetUpdate($workTarget, $user)
    {
        switch ($workTarget->type) {
            case 'awal_tahun':
                if ($user->isPPP() && $workTarget->evaluation->ppp_id !== $user->id) {
                    abort(403, 'Anda bukan PPP yang bertanggungjawab untuk penilaian ini');
                }
                break;

            case 'pertengahan_tahun':
                if (!$user->isPYD()) {
                    abort(403, 'Hanya PYD boleh mengemaskini kajian semula pertengahan tahun');
                }
                break;

            case 'akhir_tahun':
                if (!$user->isPYD() && !$user->isPPP()) {
                    abort(403, 'Hanya PYD atau PPP boleh mengemaskini laporan akhir tahun');
                }
                break;
        }
    }

    private function handleWorkTargetNotifications($workTarget, $user)
    {
        switch ($workTarget->type) {
            case 'awal_tahun':
                if ($user->isPYD()) {
                    $this->createNotification(
                        $workTarget->evaluation->ppp_id,
                        'PYD telah menghantar Sasaran Kerja Tahunan untuk kelulusan anda',
                        route('work-targets.show', $workTarget->id)
                    );
                } elseif ($user->isPPP() && $workTarget->approved) {
                    $this->createNotification(
                        $workTarget->evaluation->pyd_id,
                        'PPP telah meluluskan Sasaran Kerja Tahunan anda',
                        route('work-targets.show', $workTarget->id)
                    );
                }
                break;

            case 'pertengahan_tahun':
                $this->createNotification(
                    $workTarget->evaluation->ppp_id,
                    'PYD telah mengemaskini Kajian Semula Sasaran Kerja Tahunan Pertengahan Tahun',
                    route('work-targets.show', $workTarget->id)
                );
                break;

            case 'akhir_tahun':
                if ($user->isPYD()) {
                    $this->createNotification(
                        $workTarget->evaluation->ppp_id,
                        'PYD telah menghantar Laporan Akhir Tahun',
                        route('work-targets.show', $workTarget->id)
                    );
                } elseif ($user->isPPP()) {
                    $this->createNotification(
                        $workTarget->evaluation->pyd_id,
                        'PPP telah mengemaskini Laporan Akhir Tahun',
                        route('work-targets.show', $workTarget->id)
                    );
                }
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

    private function processInitialWorkTarget($workTarget, $items)
    {
        $workTarget->items()->delete();
        
        foreach ($items as $item) {
            $workTarget->items()->create([
                'activity' => $item['activity'],
                'performance_indicator' => $item['performance_indicator']
            ]);
        }
    }
    
    private function processMidYearReview($workTarget, $addedItems, $removedItems)
    {
        // Add new items
        foreach ($addedItems as $item) {
            $workTarget->items()->create([
                'activity' => $item['activity'],
                'performance_indicator' => $item['performance_indicator'],
                'is_added' => true
            ]);
        }
    
        // Mark removed items
        foreach ($removedItems as $item) {
            if ($item['is_removed'] ?? false) {
                $workTarget->items()->create([
                    'activity' => $item['activity'],
                    'is_removed' => true
                ]);
            }
        }
    }
    
    private function sendWorkTargetNotifications($workTarget, $user)
    {
        $evaluation = $workTarget->evaluation;
        $message = '';
        $recipientId = null;
    
        switch ($workTarget->type) {
            case 'awal_tahun':
                if ($user->isPYD()) {
                    $message = 'PYD telah menghantar SKT awal tahun untuk kelulusan anda';
                    $recipientId = $evaluation->ppp_id;
                } elseif ($user->isPPP() && $workTarget->approved) {
                    $message = 'PPP telah meluluskan SKT awal tahun anda';
                    $recipientId = $evaluation->pyd_id;
                }
                break;
                
            case 'pertengahan_tahun':
                $message = 'PYD telah mengemaskini kajian semula pertengahan tahun';
                $recipientId = $evaluation->ppp_id;
                break;
                
            case 'akhir_tahun':
                if ($user->isPYD()) {
                    $message = 'PYD telah menghantar laporan akhir tahun';
                    $recipientId = $evaluation->ppp_id;
                } elseif ($user->isPPP()) {
                    $message = 'PPP telah mengemaskini laporan akhir tahun';
                    $recipientId = $evaluation->pyd_id;
                }
                break;
        }
    
        if ($message && $recipientId) {
            Notification::create([
                'user_id' => $recipientId,
                'message' => $message,
                'action_url' => route('evaluations.show', $evaluation->id)
            ]);
        }
    }

}