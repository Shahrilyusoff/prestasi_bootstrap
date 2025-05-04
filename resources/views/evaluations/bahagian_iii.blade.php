<div class="accordion-item">
    <h2 class="accordion-header" id="headingThree">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#section-bahagian_iii">
            Bahagian III - Penghasilan Kerja (Wajaran 50%)
        </button>
    </h2>
    <div id="section-bahagian_iii" class="accordion-collapse collapse" data-bs-parent="#evaluationAccordion">
        <div class="accordion-body">
            <form method="POST" action="{{ route('evaluations.marks.save', $evaluation) }}">
                @csrf
                
                <p class="text-muted">Pegawai Penilai dikehendaki memberikan penilaian berdasarkan pencapaian kerja sebenar PYD berbanding dengan SKT yang ditetapkan. Penilaian hendaklah berasaskan kepada penjelasan setiap kriteria yang dinyatakan di bawah dengan menggunakan skala 1 hingga 10:</p>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th width="60%">Kriteria (Dinilai berasaskan SKT)</th>
                                <th width="20%">PPP</th>
                                <th width="20%">PPK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluation->marks->where('criteria.section.code', 'bahagian_iii') as $mark)
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
                                    <strong>{{ $evaluation->marks->where('criteria.section.code', 'bahagian_iii')->sum('ppp_mark') * 5 }} / 50</strong>
                                </td>
                                <td>
                                    <strong>{{ $evaluation->marks->where('criteria.section.code', 'bahagian_iii')->sum('ppk_mark') * 5 }} / 50</strong>
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