<?php

namespace Database\Seeders;

use App\Models\PydGroup;
use Illuminate\Database\Seeder;

class PydGroupsTableSeeder extends Seeder
{
    public function run()
    {
        $groups = [
            ['name' => 'Pengurusan dan Professional', 'description' => 'Pegawai kumpulan pengurusan dan professional'],
            ['name' => 'Perkhidmatan Sokongan (I)', 'description' => 'Pegawai kumpulan perkhidmatan sokongan (i)'],
            ['name' => 'Perkhidmatan Sokongan (II)', 'description' => 'Pegawai kumpulan perkhidmatan sokongan (ii)'],
        ];

        foreach ($groups as $group) {
            PydGroup::create($group);
        }
    }
}