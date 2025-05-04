<div class="accordion-item">
    <h2 class="accordion-header" id="headingNine">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#section-bahagian_ix">
            Bahagian IX - Ulasan Keseluruhan dan Pengesahan oleh PPK
        </button>
    </h2>
    <div id="section-bahagian_ix" class="accordion-collapse collapse" data-bs-parent="#evaluationAccordion">
        <div class="accordion-body">
            <form method="POST" action="{{ route('evaluations.submit', [$evaluation, 'bahagian_ix']) }}">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tempoh PYD bertugas di bawah pengawasan:</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="supervision_year" class="form-control" placeholder="Tahun" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="supervision_month" class="form-control" placeholder="Bulan" min="1" max="12" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">2. PPK hendaklah memberi ulasan keseluruhan pencapaian prestasi PYD berasaskan ulasan keseluruhan oleh PPP</label>
                    <textarea name="overall_review" class="form-control" rows="5" required></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Nama PPK:</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jawatan:</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->position }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kementerian/Jabatan:</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->ministry_department }}" readonly>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        Selesaikan Penilaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>