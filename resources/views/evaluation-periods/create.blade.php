@extends('layouts.app')

@section('header')
    <div>Tambah Tempoh Penilaian Baru</div>
    <div>
        <a href="{{ route('evaluation-periods.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('evaluation-periods.store') }}">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Tempoh</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-label">Jenis Penilaian</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">Pilih Jenis</option>
                        <option value="yearly">Tahunan</option>
                        <option value="mid_year">Pertengahan Tahun</option>
                        <option value="adhoc">Ad-Hoc</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Tarikh Mula</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                    @error('start_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Tarikh Tamat</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                    @error('end_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active">
                <label class="form-check-label" for="is_active">
                    Aktifkan tempoh ini sekarang
                </label>
                <small class="d-block text-muted">Jika diaktifkan, semua tempoh lain akan dinyahaktifkan</small>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

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