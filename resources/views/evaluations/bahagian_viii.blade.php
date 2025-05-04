<div class="accordion-item">
    <h2 class="accordion-header" id="headingEight">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#section-bahagian_viii">
            Bahagian VIII - Ulasan Keseluruhan dan Pengesahan oleh PPP
        </button>
    </h2>
    <div id="section-bahagian_viii" class="accordion-collapse collapse" data-bs-parent="#evaluationAccordion">
        <div class="accordion-body">
            <form method="POST" action="{{ route('evaluations.submit', [$evaluation, 'bahagian_viii']) }}">
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
                    <label class="form-label">2. Penilai Pertama hendaklah memberi ulasan keseluruhan prestasi PYD:</label>
                    <div class="mb-3">
                        <label class="form-label">i) Prestasi keseluruhan</label>
                        <textarea name="overall_performance" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ii) Kemajuan kerjaya</label>
                        <textarea name="career_progress" class="form-control" rows="3" required></textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="informed" name="informed" required>
                        <label class="form-check-label" for="informed">
                            Adalah disahkan bahawa prestasi pegawai ini telah dimaklumkan kepada PYD
                        </label>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Nama PPP:</label>
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
                        Hantar Ulasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>