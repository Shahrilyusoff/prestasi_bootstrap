<?php

namespace App\Http\Controllers;

use App\Models\EvaluationPeriod;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        EvaluationPeriod::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'is_active' => false
        ]);

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

        $evaluationPeriod->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type
        ]);

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

        $status = $evaluationPeriod->fresh()->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Tempoh penilaian berjaya $status");
    }

    public function massAssign(EvaluationPeriod $evaluationPeriod)
    {
        // Your mass assignment logic here
    }

    public function storeMassAssign(Request $request, EvaluationPeriod $evaluationPeriod)
    {
        // Your mass assignment store logic here
    }
}