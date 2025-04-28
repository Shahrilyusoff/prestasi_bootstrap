<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserTypesTableSeeder::class,
            PydGroupsTableSeeder::class,
            EvaluationSectionsTableSeeder::class,
            EvaluationCriteriasTableSeeder::class,
        ]);
    }
}