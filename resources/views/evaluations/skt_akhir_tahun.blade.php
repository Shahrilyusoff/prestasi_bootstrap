<div class="accordion-item">
    <h2 class="accordion-header" id="headingSKTAkhir">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#section-skt_akhir_tahun">
            SKT Akhir Tahun - Laporan Pencapaian
        </button>
    </h2>
    <div id="section-skt_akhir_tahun" class="accordion-collapse collapse" data-bs-parent="#sktAccordion">
        <div class="accordion-body">
            <form method="POST" action="{{ route('work-targets.store') }}">
                @csrf
                <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">
                <input type="hidden" name="type" value="akhir_tahun">
                
                <div class="mb-4">
                    <h5>1. Laporan/Ulasan Oleh PYD</h5>
                    <textarea name="pyd_report" class="form-control" rows="5" required>{{ $workTarget->pyd_report ?? '' }}</textarea>
                </div>

                @if(auth()->user()->isPPP())
                <div class="mb-4">
                    <h5>2. Laporan/Ulasan Oleh PPP</h5>
                    <textarea name="ppp_report" class="form-control" rows="5" required>{{ $workTarget->ppp_report ?? '' }}</textarea>
                </div>
                @endif

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        Hantar Laporan Akhir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>