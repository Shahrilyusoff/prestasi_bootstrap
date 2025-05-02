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
                           value="{{ old('name', $evaluationPeriod->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-label">Jenis Penilaian</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="yearly" {{ old('type', $evaluationPeriod->type) == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        <option value="mid_year" {{ old('type', $evaluationPeriod->type) == 'mid_year' ? 'selected' : '' }}>Pertengahan Tahun</option>
                        <option value="adhoc" {{ old('type', $evaluationPeriod->type) == 'adhoc' ? 'selected' : '' }}>Ad-Hoc</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Tarikh Mula</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ old('start_date', $evaluationPeriod->start_date->format('Y-m-d')) }}" required>
                    @error('start_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Tarikh Tamat</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ old('end_date', $evaluationPeriod->end_date->format('Y-m-d')) }}" required>
                    @error('end_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                    {{ $evaluationPeriod->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Aktifkan tempoh ini sekarang
                </label>
                <small class="d-block text-muted">Jika diaktifkan, semua tempoh lain akan dinyahaktifkan</small>
            </div>

            <div class="d-flex justify-content-between">
                <div>
                    @if($evaluationPeriod->evaluations()->count() == 0)
                        <button type="button" class="btn btn-danger" 
                            onclick="if(confirm('Adakah anda pasti ingin memadam tempoh ini?')) { document.getElementById('delete-form').submit(); }">
                            <i class="fas fa-trash"></i> Padam
                        </button>
                    @else
                        <button type="button" class="btn btn-danger" disabled title="Tidak boleh dipadam kerana terdapat penilaian">
                            <i class="fas fa-trash"></i> Padam
                        </button>
                    @endif
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

        @if($evaluationPeriod->evaluations()->count() == 0)
            <form id="delete-form" action="{{ route('evaluation-periods.destroy', $evaluationPeriod) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</div>

@if($evaluationPeriod->is_active)
<div class="card mt-4">
    <div class="card-header bg-success text-white">
        Tugasan Penilai
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="mb-0">Senarai Penilaian untuk Tempoh Ini</h5>
            <a href="{{ route('evaluation-periods.mass-assign', $evaluationPeriod) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-users"></i> Tugasan Pukal
            </a>
        </div>
        
        @if($evaluationPeriod->evaluations()->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>PYD</th>
                            <th>PPP</th>
                            <th>PPK</th>
                            <th>Status</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evaluationPeriod->evaluations()->with(['pyd', 'ppp', 'ppk'])->get() as $evaluation)
                        <tr>
                            <td>{{ $evaluation->pyd->name }}</td>
                            <td>{{ $evaluation->ppp->name }}</td>
                            <td>{{ $evaluation->ppk->name }}</td>
                            <td>
                                <span class="badge bg-{{ [
                                    'draf' => 'secondary',
                                    'pyd_submit' => 'primary',
                                    'ppp_submit' => 'info',
                                    'selesai' => 'success'
                                ][$evaluation->status] }}">
                                    {{ ucfirst(str_replace('_', ' ', $evaluation->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('evaluations.show', $evaluation) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning mb-0">
                Tiada penilaian untuk tempoh ini. Sila buat tugasan penilai.
            </div>
        @endif
    </div>
</div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date for end date based on start date
        document.getElementById('start_date').addEventListener('change', function() {
            const endDateField = document.getElementById('end_date');
            endDateField.min = this.value;
            if (endDateField.value && endDateField.value < this.value) {
                endDateField.value = this.value;
            }
        });
    });
</script>
@endpush
@endsection