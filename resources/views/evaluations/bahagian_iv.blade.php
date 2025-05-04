<div class="accordion-item">
    <h2 class="accordion-header" id="headingFour">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#section-bahagian_iv">
            Bahagian IV - Pengetahuan dan Kemahiran (Wajaran 25%)
        </button>
    </h2>
    <div id="section-bahagian_iv" class="accordion-collapse collapse" data-bs-parent="#evaluationAccordion">
        <div class="accordion-body">
            <form method="POST" action="{{ route('evaluations.marks.save', $evaluation) }}">
                @csrf
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th width="60%">Kriteria</th>
                                <th width="20%">PPP</th>
                                <th width="20%">PPK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluation->marks->where('criteria.section.code', 'bahagian_iv') as $mark)
                            <tr>
                                <td>
                                    <strong>{{ $mark->criteria->criteria }}</strong>
                                    <input type="hidden" name="marks[{{ $mark->id }}][criteria_id]" value="{{ $mark->criteria_id }}">
                                </td>
                                <td>
                                    @if(auth()->user()->isPPP())
                                        <input type="number" name="marks[{{ $mark->id }}][ppp_mark]" 
                                            class="form-control mark-input" min="1" max="10" 
                                            value="{{ $mark->ppp_mark ?? '' }}" required>
                                    @else
                                        {{ $mark->ppp_mark ?? '-' }}
                                    @endif
                                </td>
                                <td>
                                    @if(auth()->user()->isPPK())
                                        <input type="number" name="marks[{{ $mark->id }}][ppk_mark]" 
                                            class="form-control mark-input" min="1" max="10" 
                                            value="{{ $mark->ppk_mark ?? '' }}" required>
                                    @else
                                        {{ $mark->ppk_mark ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            <tr class="table-secondary">
                                <td><strong>Jumlah markah mengikut wajaran</strong></td>
                                <td>
                                    <strong>{{ $evaluation->marks->where('criteria.section.code', 'bahagian_iv')->sum('ppp_mark') * 2.5 }} / 25</strong>
                                </td>
                                <td>
                                    <strong>{{ $evaluation->marks->where('criteria.section.code', 'bahagian_iv')->sum('ppk_mark') * 2.5 }} / 25</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if(auth()->user()->isPPP() || auth()->user()->isPPK())
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        Simpan Penilaian
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>