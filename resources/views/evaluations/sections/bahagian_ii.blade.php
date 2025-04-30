<div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#section-bahagian_ii">
            Bahagian II - Kegiatan dan Sumbangan di Luar Tugas Rasmi/Latihan
        </button>
    </h2>
    <div id="section-bahagian_ii" class="accordion-collapse collapse show" data-bs-parent="#evaluationAccordion">
        <div class="accordion-body">
            <form method="POST" action="{{ route('evaluations.submit', [$evaluation, 'bahagian_ii']) }}">
                @csrf
                
                <div class="mb-4">
                    <h5>1. Kegiatan dan Sumbangan di Luar Tugas Rasmi</h5>
                    <p class="text-muted">Senaraikan kegiatan dan sumbangan di luar tugas rasmi seperti sukan/pertubuhan/sumbangan kreatif di peringkat Komuniti/Jabatan/Daerah/Negeri/Negara/Antarabangsa yang berfaedah kepada Organisasi/Komuniti/Negara pada tahun yang dinilai.</p>
                    
                    <div class="table-responsive">
                        <table class="table" id="activities-table">
                            <thead>
                                <tr>
                                    <th width="50%">Kegiatan/Aktiviti/Sumbangan</th>
                                    <th>Peringkat (Jawatan/Pencapaian)</th>
                                    <th width="50px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic rows will be added here -->
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" id="add-activity">
                        <i class="fas fa-plus"></i> Tambah Kegiatan
                    </button>
                </div>

                <div class="mb-4">
                    <h5>2. Latihan</h5>
                    
                    <div class="mb-3">
                        <h6>i) Program Latihan yang Dihadiri</h6>
                        <div class="table-responsive">
                            <table class="table" id="trainings-table">
                                <thead>
                                    <tr>
                                        <th>Nama Latihan (Sijil jika ada)</th>
                                        <th>Tarikh/Tempoh</th>
                                        <th>Tempat</th>
                                        <th width="50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamic rows will be added here -->
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary" id="add-training">
                            <i class="fas fa-plus"></i> Tambah Latihan
                        </button>
                    </div>

                    <div class="mb-3">
                        <h6>ii) Latihan yang Diperlukan</h6>
                        <div class="table-responsive">
                            <table class="table" id="required-trainings-table">
                                <thead>
                                    <tr>
                                        <th>Nama/Bidang Latihan</th>
                                        <th>Sebab Diperlukan</th>
                                        <th width="50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamic rows will be added here -->
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary" id="add-required-training">
                            <i class="fas fa-plus"></i> Tambah Latihan
                        </button>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmation" required>
                    <label class="form-check-label" for="confirmation">
                        Saya mengesahkan bahawa semua kenyataan di atas adalah benar
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        Hantar Bahagian II
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Activities table
    $('#add-activity').click(function() {
        $('#activities-table tbody').append(`
            <tr>
                <td><input type="text" name="activities[][description]" class="form-control" required></td>
                <td><input type="text" name="activities[][level]" class="form-control" required></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
            </tr>
        `);
    });

    // Trainings table
    $('#add-training').click(function() {
        $('#trainings-table tbody').append(`
            <tr>
                <td><input type="text" name="trainings[][name]" class="form-control" required></td>
                <td><input type="text" name="trainings[][date]" class="form-control" required></td>
                <td><input type="text" name="trainings[][place]" class="form-control" required></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
            </tr>
        `);
    });

    // Required trainings table
    $('#add-required-training').click(function() {
        $('#required-trainings-table tbody').append(`
            <tr>
                <td><input type="text" name="required_trainings[][name]" class="form-control" required></td>
                <td><input type="text" name="required_trainings[][reason]" class="form-control" required></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
            </tr>
        `);
    });

    // Remove row
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });
</script>
@endpush