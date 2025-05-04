<div class="accordion-item">
    <h2 class="accordion-header" id="headingSKTMid">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#section-skt_pertengahan_tahun">
            SKT Pertengahan Tahun - Kajian Semula
        </button>
    </h2>
    <div id="section-skt_pertengahan_tahun" class="accordion-collapse collapse" data-bs-parent="#sktAccordion">
        <div class="accordion-body">
            <form method="POST" action="{{ route('work-targets.store') }}">
                @csrf
                <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">
                <input type="hidden" name="type" value="pertengahan_tahun">
                
                <div class="mb-4">
                    <h5>1. Aktiviti/Projek Yang Ditambah</h5>
                    <p class="text-muted">PYD hendaklah menyenaraikan aktiviti/projek yang ditambah berserta petunjuk prestasinya setelah berbincang dengan PPP</p>
                    
                    <div class="table-responsive">
                        <table class="table" id="added-activities-table">
                            <thead>
                                <tr>
                                    <th width="50%">Aktiviti/Projek</th>
                                    <th>Petunjuk Prestasi</th>
                                    <th width="50px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workTarget->items->where('is_added', true) ?? [] as $item)
                                <tr>
                                    <td>
                                        <input type="text" name="added_items[{{ $loop->index }}][activity]" 
                                            class="form-control" value="{{ $item->activity }}" required>
                                        <input type="hidden" name="added_items[{{ $loop->index }}][is_added]" value="1">
                                    </td>
                                    <td>
                                        <input type="text" name="added_items[{{ $loop->index }}][performance_indicator]" 
                                            class="form-control" value="{{ $item->performance_indicator }}" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" id="add-activity">
                        <i class="fas fa-plus"></i> Tambah Aktiviti
                    </button>
                </div>

                <div class="mb-4">
                    <h5>2. Aktiviti/Projek Yang Digugurkan</h5>
                    <div class="table-responsive">
                        <table class="table" id="removed-activities-table">
                            <thead>
                                <tr>
                                    <th>Aktiviti/Projek</th>
                                    <th width="50px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($initialTargets as $item)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                name="removed_items[{{ $loop->index }}][is_removed]" 
                                                value="1" id="remove-{{ $item->id }}"
                                                {{ $workTarget->items->where('activity', $item->activity)->where('is_removed', true)->count() ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remove-{{ $item->id }}">
                                                {{ $item->activity }} ({{ $item->performance_indicator }})
                                            </label>
                                            <input type="hidden" name="removed_items[{{ $loop->index }}][activity]" value="{{ $item->activity }}">
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        Hantar Kajian Semula
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $('#add-activity').click(function() {
        const rowCount = $('#added-activities-table tbody tr').length;
        $('#added-activities-table tbody').append(`
            <tr>
                <td>
                    <input type="text" name="added_items[${rowCount}][activity]" class="form-control" required>
                    <input type="hidden" name="added_items[${rowCount}][is_added]" value="1">
                </td>
                <td>
                    <input type="text" name="added_items[${rowCount}][performance_indicator]" class="form-control" required>
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