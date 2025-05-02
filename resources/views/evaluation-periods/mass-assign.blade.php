@extends('layouts.app')

@section('header')
    <div>Penugasan Pukal Penilai</div>
    <div>
        <a href="{{ route('evaluation-periods.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        Tempoh: {{ $evaluationPeriod->name }} ({{ $evaluationPeriod->start_date->format('d/m/Y') }} - {{ $evaluationPeriod->end_date->format('d/m/Y') }})
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('evaluation-periods.store-mass-assign', $evaluationPeriod) }}">
            @csrf
            
            <div class="table-responsive">
                <table class="table" id="assignments-table">
                    <thead>
                        <tr>
                            <th>PYD</th>
                            <th>PPP</th>
                            <th>PPK</th>
                            <th>Kumpulan</th>
                            <th width="50px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="assignments[0][pyd_id]" class="form-select" required>
                                    <option value="">Pilih PYD</option>
                                    @foreach($pydUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="assignments[0][ppp_id]" class="form-select" required>
                                    <option value="">Pilih PPP</option>
                                    @foreach($pppUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="assignments[0][ppk_id]" class="form-select" required>
                                    <option value="">Pilih PPK</option>
                                    @foreach($ppkUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="assignments[0][pyd_group_id]" class="form-select" required>
                                    @foreach($pydGroups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between mt-3">
                <button type="button" id="add-assignment" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Baris
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Penugasan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let rowCount = 1;
        
        $('#add-assignment').click(function() {
            $('#assignments-table tbody').append(`
                <tr>
                    <td>
                        <select name="assignments[${rowCount}][pyd_id]" class="form-select" required>
                            <option value="">Pilih PYD</option>
                            @foreach($pydUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="assignments[${rowCount}][ppp_id]" class="form-select" required>
                            <option value="">Pilih PPP</option>
                            @foreach($pppUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="assignments[${rowCount}][ppk_id]" class="form-select" required>
                            <option value="">Pilih PPK</option>
                            @foreach($ppkUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="assignments[${rowCount}][pyd_group_id]" class="form-select" required>
                            @foreach($pydGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            rowCount++;
        });

        $(document).on('click', '.remove-row', function() {
            if ($('#assignments-table tbody tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                alert('Anda mesti mempunyai sekurang-kurangnya satu baris');
            }
        });
    });
</script>
@endpush