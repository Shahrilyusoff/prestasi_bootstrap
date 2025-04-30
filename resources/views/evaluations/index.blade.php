@extends('layouts.app')

@section('header')
    <div>Senarai Penilaian</div>
    <div>
        @can('create', App\Models\Evaluation::class)
            <a href="{{ route('evaluations.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Penilaian Baru
            </a>
        @endcan
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>PYD</th>
                        <th>PPP</th>
                        <th>PPK</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluations as $evaluation)
                    <tr>
                        <td>{{ $evaluation->year }}</td>
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
                            @can('update', $evaluation)
                                <a href="{{ route('evaluations.edit', $evaluation) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan
                            @can('delete', $evaluation)
                                <form action="{{ route('evaluations.destroy', $evaluation) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Adakah anda pasti?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $evaluations->links() }}
    </div>
</div>
@endsection