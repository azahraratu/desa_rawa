<?php

namespace App\Exports;

use App\Models\Warga;
use App\Support\ExcelFormat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Export data kependudukan ke Excel. NIK/No KK ditampilkan hanya untuk unduhan
 * resmi Admin (data ini sudah terenkripsi di database, didekripsi khusus saat
 * proses export dan tidak pernah dikirim mentah lewat query database).
 */
class WargaExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStyles, WithTitle
{
    /** Format tanggal bertipe Date asli (bukan teks) supaya bisa diurutkan/difilter di Excel. */
    private const FORMAT_TANGGAL = 'dd-mm-yyyy';

    /** Format jam bertipe Time asli, terpisah dari kolom tanggal. */
    private const FORMAT_JAM = 'hh:mm';

    public function __construct(
        protected ?int $dusunId = null,
        protected ?string $dari = null,
        protected ?string $sampai = null,
    ) {}

    public function collection(): Collection
    {
        $query = Warga::with(['dusun', 'rt'])->orderBy('nama');

        if ($this->dusunId) {
            $query->where('dusun_id', $this->dusunId);
        }

        if ($this->dari && $this->sampai) {
            $query->whereBetween('created_at', ["{$this->dari} 00:00:00", "{$this->sampai} 23:59:59"]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No', 'NIK', 'No. KK', 'Nama', 'Jenis Kelamin', 'Status dalam Keluarga', 'RT', 'Tempat Lahir', 'Tanggal Lahir',
            'Nama Ayah', 'Nama Ibu', 'Alamat', 'Dusun', 'No. HP', 'Status Ekonomi', 'Status Pernikahan', 'Pendidikan',
            'Pekerjaan', 'Status Kependudukan', 'Tanggal Meninggal', 'Tanggal Diinput ke Sistem', 'Jam Diinput ke Sistem',
        ];
    }

    public function map($warga): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $warga->nik,
            $warga->no_kk,
            $warga->nama,
            $warga->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $warga->labelHubunganKeluargaSaya(),
            $warga->rt->nama_rt ?? '-',
            $warga->tempat_lahir,
            ExcelFormat::tanggal($warga->tanggal_lahir),
            $warga->nama_ayah,
            $warga->nama_ibu,
            $warga->alamat,
            $warga->dusun->nama_dusun ?? '-',
            $warga->no_hp,
            str_replace('_', ' ', ucfirst($warga->status_ekonomi)),
            str_replace('_', ' ', ucfirst($warga->status_pernikahan)),
            strtoupper($warga->status_pendidikan),
            $warga->pekerjaan,
            $warga->status_kependudukan === 'meninggal' ? 'Meninggal Dunia' : ($warga->status_kependudukan === 'pindah' ? 'Pindah Domisili' : 'Hidup'),
            ExcelFormat::tanggal($warga->tanggal_meninggal),
            ExcelFormat::tanggal($warga->created_at),
            ExcelFormat::jam($warga->created_at),
        ];
    }

    public function title(): string
    {
        return 'Data Kependudukan';
    }

    /**
     * Menetapkan tipe kolom Date/Time asli di Excel (sebelumnya tanggal ditulis
     * sebagai teks biasa sehingga kolomnya bertipe "General", bukan "Date").
     */
    public function columnFormats(): array
    {
        return [
            'I' => self::FORMAT_TANGGAL, // Tanggal Lahir
            'T' => self::FORMAT_TANGGAL, // Tanggal Meninggal
            'U' => self::FORMAT_TANGGAL, // Tanggal Diinput ke Sistem
            'V' => self::FORMAT_JAM,     // Jam Diinput ke Sistem (dipisah dari tanggal)
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F5E37'],
            ], 'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]],
        ];
    }
}
