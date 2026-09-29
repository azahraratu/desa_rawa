<?php

namespace Database\Seeders;

use App\Models\SuratType;
use Illuminate\Database\Seeder;

class SuratTypeSeeder extends Seeder
{
    public function run(): void
    {
        $jenis = [
            ['kode' => 'SKTM', 'nama' => 'Surat Keterangan Tidak Mampu', 'persyaratan' => 'Fotokopi KTP, Fotokopi KK', 'label_lampiran' => 'Foto/Scan KTP & Kartu Keluarga (KK)'],
            ['kode' => 'PENGANTAR-KTP', 'nama' => 'Surat Pengantar Pembuatan KTP', 'persyaratan' => 'Fotokopi KK', 'label_lampiran' => 'Foto/Scan Kartu Keluarga (KK)'],
            ['kode' => 'PENGANTAR-KK', 'nama' => 'Surat Pengantar Pembuatan Kartu Keluarga', 'persyaratan' => 'Fotokopi KTP seluruh anggota keluarga', 'label_lampiran' => 'Foto/Scan KTP Seluruh Anggota Keluarga'],
            ['kode' => 'DOMISILI', 'nama' => 'Surat Keterangan Domisili', 'persyaratan' => 'Fotokopi KTP, Fotokopi KK', 'label_lampiran' => 'Foto/Scan KTP & Kartu Keluarga (KK)'],
            ['kode' => 'USAHA', 'nama' => 'Surat Keterangan Usaha', 'persyaratan' => 'Fotokopi KTP, Foto lokasi usaha', 'label_lampiran' => 'Foto/Scan KTP & Foto Lokasi Usaha'],
            ['kode' => 'BELUM-MENIKAH', 'nama' => 'Surat Keterangan Belum Menikah', 'persyaratan' => 'Fotokopi KTP, Fotokopi KK', 'label_lampiran' => 'Foto/Scan KTP & Kartu Keluarga (KK)'],
            ['kode' => 'SUDAH-MENIKAH', 'nama' => 'Surat Keterangan Sudah Menikah', 'persyaratan' => 'Fotokopi KTP, Fotokopi Buku Nikah', 'label_lampiran' => 'Foto/Scan KTP & Buku Nikah'],
            ['kode' => 'KELAHIRAN', 'nama' => 'Surat Keterangan Kelahiran', 'persyaratan' => 'Fotokopi KTP orang tua, Fotokopi KK, Surat keterangan bidan/RS', 'label_lampiran' => 'Foto/Scan KTP Orang Tua, KK & Surat Keterangan Bidan/RS'],
            ['kode' => 'KEMATIAN', 'nama' => 'Surat Keterangan Kematian', 'persyaratan' => 'Fotokopi KTP almarhum/ahli waris, Fotokopi KK', 'label_lampiran' => 'Foto/Scan KTP Almarhum & Kartu Keluarga (KK)'],
            ['kode' => 'PENGANTAR-SKCK', 'nama' => 'Surat Pengantar SKCK', 'persyaratan' => 'Fotokopi KTP, Fotokopi KK', 'label_lampiran' => 'Foto/Scan KTP & Kartu Keluarga (KK)'],
            ['kode' => 'AHLI-WARIS', 'nama' => 'Surat Keterangan Ahli Waris', 'persyaratan' => 'Fotokopi KTP seluruh ahli waris, Fotokopi KK, Surat Kematian', 'label_lampiran' => 'Foto/Scan Surat Kematian & KTP Seluruh Ahli Waris (bisa lebih dari 1 file)'],
        ];

        foreach ($jenis as $item) {
            SuratType::updateOrCreate(['kode' => $item['kode']], $item + ['perlu_lampiran' => true, 'aktif' => true]);
        }
    }
}
