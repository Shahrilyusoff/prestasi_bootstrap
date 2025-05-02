@extends('layouts.app')

@section('header')
    <div>Pengurusan Tempoh Penilaian</div>
    <div>
        <a href="{{ route('evaluation-periods.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tempoh Baru
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tarikh Mula</th>
                        <th>Tarikh Tamat</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($periods as $period)
                    <tr>
                        <td>{{ $period->name }}</td>
                        <td>{{ $period->start_date->format('d/m/Y') }}</td>
                        <td>{{ $period->end_date->format('d/m/Y') }}</td>
                        <td>
                            @if($period->type === 'yearly')
                                Tahunan
                            @elseif($period->type === 'mid_year')
                                Pertengahan Tahun
                            @else
                                Ad-Hoc
                            @endif
                        </td>
                        <td>
                            @if($period->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('evaluation-periods.edit', $period) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('evaluation-periods.toggle-active', $period) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-{{ $period->is_active ? 'danger' : 'success' }}">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            </form>
                            <form action="{{ route('evaluation-periods.destroy', $period) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Adakah anda pasti?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection