<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@desarawa.go.id'],
            [
                'name' => 'Admin Desa Rawa',
                'no_hp' => '081234567890',
                'password' => Hash::make('AdminDesaRawa#2026'),
                'role' => 'admin',
                'must_change_password' => false,
                'is_active' => true,
            ]
        );

        $this->command->info('Akun Admin dibuat -> Email: admin@desarawa.go.id | Password: AdminDesaRawa#2026');
        $this->command->warn('SEGERA GANTI password ini setelah login pertama di lingkungan produksi!');
    }
}
