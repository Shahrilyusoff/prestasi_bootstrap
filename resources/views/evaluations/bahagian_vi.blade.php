<div class="accordion-item">
    <h2 class="accordion-header" id="headingSix">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#section-bahagian_vi">
            Bahagian VI - Kegiatan dan Sumbangan di Luar Tugas Rasmi (Wajaran 5%)
        </button>
    </h2>
    <div id="section-bahagian_vi" class="accordion-collapse collapse" data-bs-parent="#evaluationAccordion">
        <div class="accordion-body">
            <form method="POST" action="{{ route('evaluations.marks.save', $evaluation) }}">
                @csrf
                
                <p class="text-muted">Berasaskan maklumat di Bahagian II perenggan 1, Pegawai Penilai dikehendaki memberi penilaian dengan menggunakan skala 1 hingga 10. Tiada sebarang markah boleh diberikan (kosong) jika PYD tidak mencatat kegiatan atau sumbangannya.</p>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th width="60%">Peringkat</th>
                                <th width="20%">PPP</th>
                                <th width="20%">PPK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['Komuniti/Jabatan', 'Daerah', 'Negeri', 'Negara', 'Antarabangsa'] as $level)
                            @php
                                $mark = $evaluation->marks
                                    ->where('criteria.section.code', 'bahagian_vi')
                                    ->first();
                            @endphp
                            <tr>
                                <td>{{ $level }}</td>
                                <td>
                                    @if(auth()->user()->isPPP())
                                        <input type="number" name="marks[{{ $mark->id }}][ppp_mark]" 
                                            class="form-control mark-input" min="0" max="10" 
                                            value="{{ $mark->ppp_mark ?? '' }}" required>
                                    @else
                                        {{ $mark->ppp_mark ?? '-' }}
                                    @endif
                                </td>
                                <td>
                                    @if(auth()->user()->isPPK())
                                        <input type="number" name="marks[{{ $mark->id }}][ppk_mark]" 
                                            class="form-control mark-input" min="0" max="10" 
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
                                    <strong>{{ $evaluation->marks->where('criteria.section.code', 'bahagian_vi')->sum('ppp_mark') * 0.5 }} / 5</strong>
                                </td>
                                <td>
                                    <strong>{{ $evaluation->marks->where('criteria.section.code', 'bahagian_vi')->sum('ppk_mark') * 0.5 }} / 5</strong>
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