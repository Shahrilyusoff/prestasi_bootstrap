@extends('layouts.app')

@section('header', 'Tambah Pengguna Baru')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nama Penuh</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Emel</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Kata Laluan</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Sahkan Kata Laluan</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="user_type_id" class="form-label">Peranan</label>
                    <select class="form-select" id="user_type_id" name="user_type_id" required>
                        @foreach($userTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="pyd_group_id" class="form-label">Kumpulan PYD</label>
                    <select class="form-select" id="pyd_group_id" name="pyd_group_id">
                        <option value="">Tiada</option>
                        @foreach($pydGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="position" class="form-label">Jawatan</label>
                    <input type="text" class="form-control" id="position" name="position">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="grade" class="form-label">Gred</label>
                    <input type="text" class="form-control" id="grade" name="grade">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="ministry_department" class="form-label">Kementerian/Jabatan</label>
                    <input type="text" class="form-control" id="ministry_department" name="ministry_department">
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection