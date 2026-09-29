<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Template Excel resmi untuk import data warga oleh Kepala Dusun.
 * Sheet 1: kolom yang wajib diisi + 1 baris contoh.
 * Sheet 2: tutorial/panduan pengisian.
 */
class WargaImportTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new TemplateDataSheet,
            new TemplatePanduanSheet,
        ];
    }
}

class TemplateDataSheet implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    public function array(): array
    {
        return [
            ['NIK', 'No KK', 'Nama', 'Jenis Kelamin', 'Status Hubungan Keluarga', 'RT', 'Tempat Lahir', 'Tanggal Lahir', 'Nama Ayah', 'Nama Ibu', 'Alamat', 'No HP', 'Status Ekonomi', 'Status Pernikahan', 'Status Pendidikan', 'Pekerjaan'],
            ['3207011203950003', '3207011203950000', 'Ahmad Faisal (CONTOH - hapus baris ini)', 'L', 'kepala_keluarga', '05', 'Ciamis', '1995-03-12', 'Nama Ayah Faisal', 'Nama Ibu Faisal', 'RT 05/RW 01', '081200000003', 'mampu', 'menikah', 'sma', 'Petani'],
            ['3207015507980004', '3207011203950000', 'Dewi Lestari (CONTOH - hapus baris ini)', 'P', 'istri', '05', 'Ciamis', '1998-07-15', 'Nama Ayah Dewi', 'Nama Ibu Dewi', 'RT 05/RW 01', '081200000004', 'mampu', 'menikah', 's1', 'Guru'],
        ];
    }

    public function title(): string
    {
        return 'Data Warga';
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:P1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:P1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('0F5E37');
        $sheet->getStyle('A2:P3')->getFont()->setItalic(true)->getColor()->setRGB('999999');

        // Format kolom NIK, No KK & RT sebagai TEXT agar tidak berubah jadi notasi ilmiah / hilang angka nolnya.
        $sheet->getStyle('A2:B1000')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('F2:F1000')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('H2:H1000')->getNumberFormat()->setFormatCode('@');

        return [];
    }
}

class TemplatePanduanSheet implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    public function array(): array
    {
        return [
            ['PANDUAN PENGISIAN TEMPLATE IMPORT DATA WARGA'],
            [''],
            ['1. Jangan mengubah urutan atau nama kolom pada sheet "Data Warga".'],
            ['2. Hapus baris contoh (baris ke-2) sebelum mengisi data asli, atau timpa langsung.'],
            ['3. Kolom NIK dan No KK WAJIB diisi 16 digit angka. Format kolom sudah diatur sebagai Teks agar angka 0 di depan tidak hilang.'],
            ['   Jika masih berubah jadi notasi ilmiah (contoh: 3.2E+15), ketik tanda kutip satu di depan angka, misalnya: '."'".'3207011203950003'],
            ['4. Kolom Jenis Kelamin diisi dengan huruf "L" (Laki-laki) atau "P" (Perempuan) saja.'],
            ['5. Kolom Status Hubungan Keluarga (posisi dalam KK) diisi salah satu: kepala_keluarga, suami, istri, anak, menantu, cucu, orang_tua, mertua, famili_lain, atau lainnya'],
            ['   Idealnya setiap No KK hanya memiliki 1 baris dengan status kepala_keluarga.'],
            ['6. Kolom RT diisi nomor RT (contoh: 05 atau 12). Jika RT tersebut belum pernah dibuat, sistem akan membuatkannya otomatis. Boleh dikosongkan.'],
            ['7. Kolom Tanggal Lahir WAJIB format: TAHUN-BULAN-TANGGAL, contoh: 1995-03-12 (untuk 12 Maret 1995).'],
            ['   Password awal login warga otomatis dibuat dari tanggal ini (format DDMMYYYY).'],
            ['8. Kolom Nama Ayah & Nama Ibu diisi nama orang tua kandung (boleh dikosongkan jika tidak diketahui).'],
            ['9. Kolom Status Ekonomi diisi salah satu: mampu, kurang_mampu, atau tidak_mampu'],
            ['10. Kolom Status Pernikahan diisi salah satu: belum_menikah, menikah, cerai_hidup, atau cerai_mati'],
            ['11. Kolom Status Pendidikan diisi salah satu: tidak_sekolah, sd, smp, sma, diploma, s1, s2, atau s3'],
            ['12. Kolom Pekerjaan & No HP boleh dikosongkan jika tidak ada datanya.'],
            ['13. Jika NIK sudah pernah diimport sebelumnya, data yang baru akan MENIMPA/memperbarui data lama (bukan membuat duplikat).'],
            ['14. Setelah selesai mengisi, simpan file (tetap format .xlsx), lalu unggah lewat menu Import Data Warga.'],
            [''],
            ['Ada kendala? Hubungi Admin Desa Rawa.'],
        ];
    }

    public function title(): string
    {
        return 'Panduan Pengisian';
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getColumnDimension('A')->setWidth(100);
        $sheet->getStyle('A1:A20')->getAlignment()->setWrapText(true);

        return [];
    }
}
