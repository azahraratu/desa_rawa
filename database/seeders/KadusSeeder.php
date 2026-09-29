<?php

namespace Database\Seeders;

use App\Models\Dusun;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KadusSeeder extends Seeder
{
    public function run(): void
    {
        $dusuns = Dusun::all();
        $admin = User::where('role', 'admin')->first();

        foreach ($dusuns as $index => $dusun) {
            $nik = str_pad('8888800'.($index + 1), 16, '0', STR_PAD_LEFT);

            $sudahAda = User::cariBerdasarkan('nik', $nik)->exists();

            if ($sudahAda) {
                continue;
            }

            User::create([
                'name' => 'Kepala '.$dusun->nama_dusun,
                'nik' => $nik,
                'no_hp' => '08111111111'.$index,
                'password' => Hash::make('KadusRawa#2026'),
                'role' => 'kadus',
                'dusun_id' => $dusun->id,
                'must_change_password' => true,
                'is_active' => true,
                'created_by' => $admin?->id,
            ]);
        }

        $this->command->info('Akun Kepala Dusun dibuat -> Password default: KadusRawa#2026 (wajib ganti saat login pertama)');
    }
}
