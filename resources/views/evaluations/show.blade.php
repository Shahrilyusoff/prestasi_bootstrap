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
                <p><strong>Kumpulan:</strong> {{ $evaluation->pydGroup->name }}</p>
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
                @if($evaluation->evaluationPeriod)
                <p><strong>Tempoh Penilaian:</strong> {{ $evaluation->evaluationPeriod->name }}</p>
                <p><strong>Tarikh:</strong> 
                    {{ $evaluation->evaluationPeriod->start_date->format('d/m/Y') }} - 
                    {{ $evaluation->evaluationPeriod->end_date->format('d/m/Y') }}
                </p>
                @endif
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
                        <a href="#section-{{ $section->code }}" 
                           class="list-group-item list-group-item-action {{ !$accessibleSections[$section->code] ? 'disabled' : '' }}"
                           @if(!$accessibleSections[$section->code]) style="pointer-events: none; color: #6c757d;" @endif>
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
                @if($activePeriod && now()->between($activePeriod->start_date, $activePeriod->end_date))
                    <div class="alert alert-info">
                        <strong>Tempoh Aktif:</strong> {{ $activePeriod->name }} ({{ $activePeriod->start_date->format('d/m/Y') }} - {{ $activePeriod->end_date->format('d/m/Y') }})
                    </div>
                @elseif($activePeriod)
                    <div class="alert alert-warning">
                        Tempoh penilaian ini telah tamat pada {{ $activePeriod->end_date->format('d/m/Y') }}
                    </div>
                @endif

                <div class="accordion" id="evaluationAccordion">
                    @foreach($sections as $section)
                        @if(($section->is_pyd_section && auth()->user()->isPYD()) || 
                            ($section->is_ppp_section && auth()->user()->isPPP()) || 
                            ($section->is_ppk_section && auth()->user()->isPPK()))
                            @include('evaluations.sections.' . $section->code, [
                                'evaluation' => $evaluation,
                                'accessible' => $accessibleSections[$section->code]
                            ])
                        @endif
                    @endforeach
                </div>

                <!-- SKT Sections -->
                @if($accessibleSections['skt_awal_tahun'] || $accessibleSections['skt_pertengahan_tahun'] || $accessibleSections['skt_akhir_tahun'])
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        Sasaran Kerja Tahunan (SKT)
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="sktAccordion">
                            @if($accessibleSections['skt_awal_tahun'])
                                @include('evaluations.sections.skt_awal_tahun', [
                                    'evaluation' => $evaluation,
                                    'workTarget' => $evaluation->workTargets->where('type', 'awal_tahun')->first()
                                ])
                            @endif
                            
                            @if($accessibleSections['skt_pertengahan_tahun'])
                                @include('evaluations.sections.skt_pertengahan_tahun', [
                                    'evaluation' => $evaluation,
                                    'workTarget' => $evaluation->workTargets->where('type', 'pertengahan_tahun')->first(),
                                    'initialTargets' => $evaluation->workTargets->where('type', 'awal_tahun')->first()->items ?? []
                                ])
                            @endif
                            
                            @if($accessibleSections['skt_akhir_tahun'])
                                @include('evaluations.sections.skt_akhir_tahun', [
                                    'evaluation' => $evaluation,
                                    'workTarget' => $evaluation->workTargets->where('type', 'akhir_tahun')->first()
                                ])
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Activate first accessible section
    document.addEventListener('DOMContentLoaded', function() {
        const firstEnabledSection = document.querySelector('.list-group-item-action:not(.disabled)');
        if (firstEnabledSection) {
            const target = firstEnabledSection.getAttribute('href');
            const accordionItem = document.querySelector(`${target}.accordion-collapse`);
            if (accordionItem) {
                new bootstrap.Collapse(accordionItem, { toggle: true });
            }
        }

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Handle mark input calculations
    document.querySelectorAll('.mark-input').forEach(input => {
        input.addEventListener('change', function() {
            const max = parseInt(this.getAttribute('max'));
            const value = parseInt(this.value) || 0;
            
            if (value > max) {
                this.value = max;
            } else if (value < 0) {
                this.value = 0;
            }
            
            // Calculate section totals if needed
            calculateSectionTotal(this.closest('table'));
        });
    });

    function calculateSectionTotal(table) {
        let total = 0;
        const inputs = table.querySelectorAll('.mark-input');
        
        inputs.forEach(input => {
            total += parseInt(input.value) || 0;
        });
        
        const totalDisplay = table.querySelector('.section-total');
        if (totalDisplay) {
            totalDisplay.textContent = total;
        }
    }
</script>
@endpush