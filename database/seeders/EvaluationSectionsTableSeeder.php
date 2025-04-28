<?php

namespace Database\Seeders;

use App\Models\EvaluationSection;
use Illuminate\Database\Seeder;

class EvaluationSectionsTableSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                'name' => 'Bahagian I - Maklumat Pegawai',
                'code' => 'bahagian_i',
                'is_pyd_section' => true,
                'is_ppp_section' => false,
                'is_ppk_section' => false,
            ],
            [
                'name' => 'Bahagian II - Kegiatan dan Sumbangan di Luar Tugas Rasmi/Latihan',
                'code' => 'bahagian_ii',
                'is_pyd_section' => true,
                'is_ppp_section' => false,
                'is_ppk_section' => false,
            ],
            [
                'name' => 'Bahagian III - Penghasilan Kerja',
                'code' => 'bahagian_iii',
                'weightage' => 50,
                'is_pyd_section' => false,
                'is_ppp_section' => true,
                'is_ppk_section' => true,
            ],
            [
                'name' => 'Bahagian IV - Pengetahuan dan Kemahiran',
                'code' => 'bahagian_iv',
                'weightage' => 25,
                'is_pyd_section' => false,
                'is_ppp_section' => true,
                'is_ppk_section' => true,
            ],
            [
                'name' => 'Bahagian V - Kualiti Peribadi',
                'code' => 'bahagian_v',
                'weightage' => 20,
                'is_pyd_section' => false,
                'is_ppp_section' => true,
                'is_ppk_section' => true,
            ],
            [
                'name' => 'Bahagian VI - Kegiatan dan Sumbangan di Luar Tugas Rasmi',
                'code' => 'bahagian_vi',
                'weightage' => 5,
                'is_pyd_section' => false,
                'is_ppp_section' => true,
                'is_ppk_section' => true,
            ],
            [
                'name' => 'Bahagian VII - Jumlah Markah Keseluruhan',
                'code' => 'bahagian_vii',
                'is_pyd_section' => false,
                'is_ppp_section' => true,
                'is_ppk_section' => true,
            ],
            [
                'name' => 'Bahagian VIII - Ulasan Keseluruhan PPP',
                'code' => 'bahagian_viii',
                'is_pyd_section' => false,
                'is_ppp_section' => true,
                'is_ppk_section' => false,
            ],
            [
                'name' => 'Bahagian IX - Ulasan Keseluruhan PPK',
                'code' => 'bahagian_ix',
                'is_pyd_section' => false,
                'is_ppp_section' => false,
                'is_ppk_section' => true,
            ],
        ];

        foreach ($sections as $section) {
            EvaluationSection::create($section);
        }
    }
}