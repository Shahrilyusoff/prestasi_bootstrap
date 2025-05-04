<div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#section-bahagian_i">
            Bahagian I - Maklumat Pegawai
        </button>
    </h2>
    <div id="section-bahagian_i" class="accordion-collapse collapse" data-bs-parent="#evaluationAccordion">
        <div class="accordion-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama:</label>
                    <input type="text" class="form-control" value="{{ $evaluation->pyd->name }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jawatan dan Gred:</label>
                    <input type="text" class="form-control" value="{{ $evaluation->pyd->position }} {{ $evaluation->pyd->grade }}" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label">Kementerian/Jabatan:</label>
                    <input type="text" class="form-control" value="{{ $evaluation->pyd->ministry_department }}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>