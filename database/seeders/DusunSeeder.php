<?php

namespace Database\Seeders;

use App\Models\Dusun;
use Illuminate\Database\Seeder;

class DusunSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Dusun I', 'Dusun II', 'Dusun III', 'Dusun IV'] as $nama) {
            Dusun::firstOrCreate(['nama_dusun' => $nama]);
        }
    }
}
