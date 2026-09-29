<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DusunSeeder::class,
            SuratTypeSeeder::class,
            AdminSeeder::class,
            KadusSeeder::class,
            WargaContohSeeder::class,
        ]);
    }
}
