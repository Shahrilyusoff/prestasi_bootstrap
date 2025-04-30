@extends('layouts.app')

@section('header')
    <div>Penilaian Prestasi - {{ $evaluation->year }}</div>
    <div>
        <a href="{{ route('evaluations.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Maklumat Penilaian
            </div>
            <div class="card-body">
                <p><strong>PYD:</strong> {{ $evaluation->pyd->name }}</p>
                <p><strong>PPP:</strong> {{ $evaluation->ppp->name }}</p>
                <p><strong>PPK:</strong> {{ $evaluation->ppk->name }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-{{ [
                        'draf' => 'secondary',
                        'pyd_submit' => 'primary',
                        'ppp_submit' => 'info',
                        'selesai' => 'success'
                    ][$evaluation->status] }}">
                        {{ ucfirst(str_replace('_', ' ', $evaluation->status)) }}
                    </span>
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                Bahagian Penilaian
            </div>
            <div class="list-group list-group-flush">
                @foreach($sections as $section)
                    @if(($section->is_pyd_section && auth()->user()->isPYD()) || 
                        ($section->is_ppp_section && auth()->user()->isPPP()) || 
                        ($section->is_ppk_section && auth()->user()->isPPK()))
                        <a href="#section-{{ $section->code }}" class="list-group-item list-group-item-action">
                            {{ $section->name }}
                            @if($section->weightage)
                                <span class="badge bg-secondary float-end">{{ $section->weightage }}%</span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div class="accordion" id="evaluationAccordion">
                    @foreach($sections as $section)
                        @include('evaluations.sections.' . $section->code)
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Activate first section
    document.addEventListener('DOMContentLoaded', function() {
        const firstSection = document.querySelector('.accordion-button');
        if (firstSection) {
            firstSection.click();
        }
    });
</script>
@endpush