<div class="accordion-item">
    <h2 class="accordion-header" id="headingSKTAwal">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#section-skt_awal_tahun">
            SKT Awal Tahun - Penetapan Sasaran Kerja Tahunan
        </button>
    </h2>
    <div id="section-skt_awal_tahun" class="accordion-collapse collapse" data-bs-parent="#sktAccordion">
        <div class="accordion-body">
            <form method="POST" action="{{ route('work-targets.store') }}">
                @csrf
                <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">
                <input type="hidden" name="type" value="awal_tahun">
                
                <p class="text-muted">PYD dan PPP hendaklah berbincang bersama sebelum menetapkan SKT dan petunjuk prestasinya</p>
                
                <div class="table-responsive">
                    <table class="table" id="skt-table">
                        <thead>
                            <tr>
                                <th width="50%">Aktiviti/Projek</th>
                                <th>Petunjuk Prestasi (Kuantiti/Kualiti/Masa/Kos)</th>
                                <th width="50px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workTarget->items ?? [] as $item)
                            <tr>
                                <td>
                                    <input type="text" name="items[{{ $loop->index }}][activity]" 
                                        class="form-control" value="{{ $item->activity }}" required>
                                </td>
                                <td>
                                    <input type="text" name="items[{{ $loop->index }}][performance_indicator]" 
                                        class="form-control" value="{{ $item->performance_indicator }}" required>
                                </td>
                                <td>
                                    @if(!$item->id)
                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-primary" id="add-skt-item">
                    <i class="fas fa-plus"></i> Tambah Aktiviti
                </button>

                @if(auth()->user()->isPPP())
                <div class="form-check mb-3 mt-3">
                    <input class="form-check-input" type="checkbox" name="approved" id="approve-skt" value="1" 
                        {{ $workTarget->approved ?? false ? 'checked' : '' }}>
                    <label class="form-check-label" for="approve-skt">
                        Saya meluluskan SKT ini
                    </label>
                </div>
                @endif

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        Simpan SKT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $('#add-skt-item').click(function() {
        const rowCount = $('#skt-table tbody tr').length;
        $('#skt-table tbody').append(`
            <tr>
                <td>
                    <input type="text" name="items[${rowCount}][activity]" class="form-control" required>
                </td>
                <td>
                    <input type="text" name="items[${rowCount}][performance_indicator]" class="form-control" required>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
    });

    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });
</script>
@endpush