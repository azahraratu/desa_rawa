<?php

namespace Database\Seeders;

use App\Models\Dusun;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Seeder;

/**
 * Data contoh warga yang "sudah diunggah oleh Kepala Dusun",
 * dipakai untuk mencoba alur registrasi warga.
 */
class WargaContohSeeder extends Seeder
{
    public function run(): void
    {
        $dusun = Dusun::first();
        $kadus = User::where('role', 'kadus')->where('dusun_id', $dusun->id)->first();

        $this->buatJikaBelumAda('3207010101990001', [
            'no_kk' => '3207010101990000',
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'status_hubungan_keluarga' => 'kepala_keluarga',
            'tempat_lahir' => 'Ciamis',
            'nama_ayah' => 'Suparjo',
            'nama_ibu' => 'Sumiati',
            'tanggal_lahir' => '1999-01-01',
            'alamat' => 'RT 01/RW 01, '.$dusun->nama_dusun,
            'no_hp' => '6281200000001',
            'dusun_id' => $dusun->id,
            'status_ekonomi' => 'mampu',
            'status_pernikahan' => 'menikah',
            'status_pendidikan' => 'sma',
            'pekerjaan' => 'Wiraswasta',
            'status_kependudukan' => 'hidup',
            'diinput_oleh' => $kadus?->id,
        ]);

        $this->buatJikaBelumAda('3207010101990002', [
            'no_kk' => '3207010101990000',
            'nama' => 'Siti Aminah',
            'jenis_kelamin' => 'P',
            'status_hubungan_keluarga' => 'istri',
            'tempat_lahir' => 'Ciamis',
            'nama_ayah' => 'Wardi',
            'nama_ibu' => 'Rohaya',
            'tanggal_lahir' => '2001-05-10',
            'alamat' => 'RT 01/RW 01, '.$dusun->nama_dusun,
            'no_hp' => '6281200000002',
            'dusun_id' => $dusun->id,
            'status_ekonomi' => 'mampu',
            'status_pernikahan' => 'menikah',
            'status_pendidikan' => 's1',
            'pekerjaan' => 'Guru',
            'status_kependudukan' => 'hidup',
            'diinput_oleh' => $kadus?->id,
        ]);

        $this->command->info('2 data contoh warga (1 KK) ditambahkan. NIK: 3207010101990001 (Budi), No KK: 3207010101990000. Password awal registrasi = tanggal lahir format ddmmyyyy, misal 01011999.');
    }

    protected function buatJikaBelumAda(string $nik, array $data): void
    {
        if (Warga::cariBerdasarkan('nik', $nik)->exists()) {
            return;
        }

        Warga::create(['nik' => $nik] + $data);
    }
}
