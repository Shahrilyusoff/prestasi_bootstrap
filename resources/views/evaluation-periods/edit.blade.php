@extends('layouts.app')

@section('header')
    <div>Kemaskini Tempoh Penilaian</div>
    <div>
        <a href="{{ route('evaluation-periods.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('evaluation-periods.update', $evaluationPeriod) }}">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Tempoh</label>
                    <input type="text" class="form-control" id="name" name="name" 
                        value="{{ $evaluationPeriod->name }}" required>
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-label">Jenis Penilaian</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="yearly" {{ $evaluationPeriod->type === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        <option value="mid_year" {{ $evaluationPeriod->type === 'mid_year' ? 'selected' : '' }}>Pertengahan Tahun</option>
                        <option value="adhoc" {{ $evaluationPeriod->type === 'adhoc' ? 'selected' : '' }}>Ad-Hoc</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Tarikh Mula</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                        value="{{ \Carbon\Carbon::parse($evaluationPeriod->start_date)->format('Y-m-d') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Tarikh Tamat</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                        value="{{ \Carbon\Carbon::parse($evaluationPeriod->end_date)->format('Y-m-d') }}" required>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Kemaskini
                </button>
            </div>
        </form>
    </div>
</div>
@endsection