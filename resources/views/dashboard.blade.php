@extends('layouts.app')

@section('header', 'Penilaian Prestasi')

@section('content')
<div class="row">
    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
        <div class="col-md-4 mb-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Pengguna</h5>
                    <p class="display-6">{{ $userCount }}</p>
                    <a href="{{ route('users.index') }}" class="btn btn-primary">Lihat Semua</a>
                </div>
            </div>
        </div>
    @endif

    <div class="col-md-4 mb-4">
        <div class="card border-success">
            <div class="card-body">
                <h5 class="card-title">Penilaian Aktif</h5>
                <p class="display-6">{{ $activeEvaluations }}</p>
                <a href="{{ route('evaluations.index') }}" class="btn btn-success">Lihat Semua</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-info">
            <div class="card-body">
                <h5 class="card-title">Notifikasi</h5>
                <p class="display-6">{{ $unreadNotifications }}</p>
                <a href="{{ route('notifications.index') }}" class="btn btn-info">Lihat Semua</a>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->isPYD())
<div class="card mt-4">
    <div class="card-header bg-warning">
        <h5 class="mb-0">Tindakan Diperlukan</h5>
    </div>
    <div class="card-body">
        @if($pendingEvaluations->count())
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingEvaluations as $evaluation)
                        <tr>
                            <td>{{ $evaluation->year }}</td>
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
                                <a href="{{ route('evaluations.show', $evaluation) }}" class="btn btn-sm btn-primary">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="mb-0">Tiada penilaian memerlukan tindakan anda.</p>
        @endif
    </div>
</div>
@endif
@endsection