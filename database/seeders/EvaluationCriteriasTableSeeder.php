<?php

namespace Database\Seeders;

use App\Models\EvaluationCriteria;
use App\Models\EvaluationSection;
use Illuminate\Database\Seeder;

class EvaluationCriteriasTableSeeder extends Seeder
{
    public function run()
    {
        // Bahagian III - Penghasilan Kerja
        $bahagianIII = EvaluationSection::where('code', 'bahagian_iii')->first();
        $criteriasIII = [
            [
                'criteria' => 'Kuantiti Hasil Kerja - Kuantiti hasil kerja seperti jumlah, bilangan, kadar, kekerapan dan sebagainya berbanding dengan sasaran kuantiti kerja yang diterapkan',
                'max_mark' => 10,
            ],
            [
                'criteria' => 'Kualiti Hasil Kerja - Dinilai dari segi kesempurnaan, teratur dan kemas',
                'max_mark' => 10,
            ],
            [
                'criteria' => 'Kualiti Hasil Kerja - Dinilai dari segi usaha dan inisiatif untuk mencapai kesempurnaan hasil kerja',
                'max_mark' => 10,
            ],
            [
                'criteria' => 'Ketepatan Masa - Kebolehan menghasilkan kerja atau melaksanakan tugas dalam tempoh masa yang ditetapkan',
                'max_mark' => 10,
            ],
            [
                'criteria' => 'Keberkesanan Hasil Kerja - Dinilai dari segi memenuhi kehendak stake-holder atau pelanggan',
                'max_mark' => 10,
            ],
        ];

        foreach ($criteriasIII as $criteria) {
            EvaluationCriteria::create([
                'section_id' => $bahagianIII->id,
                'criteria' => $criteria['criteria'],
                'max_mark' => $criteria['max_mark'],
            ]);
        }

        // Bahagian IV - Pengetahuan dan Kemahiran
        $bahagianIV = EvaluationSection::where('code', 'bahagian_iv')->first();
        $criteriasIV = [
            [
                'criteria' => 'Ilmu Pengetahuan dan Kemahiran dalam Bidang Kerja - Mempunyai ilmu pengetahuan dan kemahiran/kepakaran dalam menghasilkan kerja meliputi kebolehan mengenalpasti, menganalisis serta menyelesaikan masalah',
                'max_mark' => 10,
            ],
            [
                'criteria' => 'Pelaksanaan Dasar, Peraturan dan Arahan Pentadbiran - Kebolehan menghayati dan melaksanakan dasar, peraturan dan arahan pentadbiran berkaitan dengan bidang tugasnya',
                'max_mark' => 10,
            ],
            [
                'criteria' => 'Keberkesanan Komunikasi - Kebolehan menyampaikan maksud, pendapat, kefahaman atau arahan secara lisan dan tulisan berkaitan dengan bidang tugas merangkumi penguasaan Bahasa melalui tulisan dan lisan dengan menggunakan tatabahasa dan persembahan yang baik',
                'max_mark' => 10,
            ],
        ];

        foreach ($criteriasIV as $criteria) {
            EvaluationCriteria::create([
                'section_id' => $bahagianIV->id,
                'criteria' => $criteria['criteria'],
                'max_mark' => $criteria['max_mark'],
            ]);
        }

        // Bahagian V - Kualiti Peribadi
        $bahagianV = EvaluationSection::where('code', 'bahagian_v')->first();
        $criteriasV = [
            [
                'criteria' => 'Ciri-ciri Memimpin - Mempunyai wawasan, komitmen, kebolehan membuat keputusan, menggerak dan memberi dorongan kepada pegawai kearah pencapaian objektif organisasi',
                'max_mark' => 10,
            ],
            [
                'criteria' => 'Kebolehan Mengelola - Keupayaan dan kebolehan menggembleng segala sumber dalam kawalannya seperti kewangan, tenaga manusia, peralatan dan maklumat bagi merancang, mengatur, membahagi dan mengendalikan sesuatu tugas untuk mencapai objektif organisasi',
                'max_mark' => 10,
            ],
            [
                'criteria' => 'Disiplin - Mempunyai daya kawal diri dari segi mental dan fizikal termasuk mematuhi peraturan, menepati masa, menunaikan janji dan bersifat sabar',
                'max_mark' => 10,
            ],
            [
                'criteria' => 'Proaktif dan Inovatif - Kebolehan menjangka kemungkinan, mencipta dan mengeluarkan idea baru serta membuat pembaharuan bagi mempertingkatkan kualiti dan produktiviti organisasi',
                'max_mark' => 10,
            ],
            [
                'criteria' => 'Jalinan Hubungan dan Kerjasama - Kebolehan pegawai dalam mewujudkan suasana kerjasama yang harmoni dan mesra serta boleh menyesuaikan diri dalam semua keadaan',
                'max_mark' => 10,
            ],
        ];

        foreach ($criteriasV as $criteria) {
            EvaluationCriteria::create([
                'section_id' => $bahagianV->id,
                'criteria' => $criteria['criteria'],
                'max_mark' => $criteria['max_mark'],
            ]);
        }

        // Bahagian VI - Kegiatan dan Sumbangan di Luar Tugas Rasmi
        $bahagianVI = EvaluationSection::where('code', 'bahagian_vi')->first();
        EvaluationCriteria::create([
            'section_id' => $bahagianVI->id,
            'criteria' => 'Kegiatan dan Sumbangan di Luar Tugas Rasmi - Berasaskan maklumat di Bahagian II perenggan 1',
            'max_mark' => 10,
        ]);
    }
}