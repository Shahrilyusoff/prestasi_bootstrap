<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypesTableSeeder extends Seeder
{
    public function run()
    {
        $userTypes = [
            ['name' => 'Super Admin', 'description' => 'Pembangun sistem'],
            ['name' => 'Admin', 'description' => 'Pentadbir sistem'],
            ['name' => 'PPP', 'description' => 'Pegawai Penilai Pertama'],
            ['name' => 'PPK', 'description' => 'Pegawai Penilai Kedua'],
            ['name' => 'PYD', 'description' => 'Pegawai Yang Dinilai'],
        ];

        foreach ($userTypes as $type) {
            UserType::create($type);
        }
    }
}