<?php

namespace App\Exports;

use App\Models\Dusun;
use App\Models\SuratRequest;
use App\Models\Warga;
use App\Support\ExcelFormat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Arsip bulanan lengkap (mirip mutasi rekening): satu file Excel berisi
 * beberapa sheet - Ringkasan per Dusun, Data Warga Baru, Permohonan Surat,
 * Pengaduan, dan Data Kematian - untuk satu periode bulan tertentu.
 */
class ArsipBulananExport implements WithMultipleSheets
{
    public function __construct(
        protected string $dari,
        protected string $sampai,
        protected string $labelPeriode,
        protected ?int $dusunId = null,
    ) {}

    public function sheets(): array
    {
        return [
            new RingkasanDusunSheet($this->dari, $this->sampai, $this->labelPeriode, $this->dusunId),
            new WargaExport($this->dusunId, $this->dari, $this->sampai),
            new SuratRequestExport($this->dusunId, $this->dari, $this->sampai),
            new PengaduanExport($this->dari, $this->sampai),
            new KematianSheet($this->dari, $this->sampai, $this->dusunId),
        ];
    }
}

class RingkasanDusunSheet implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(
        protected string $dari,
        protected string $sampai,
        protected string $labelPeriode,
        protected ?int $dusunId = null,
    ) {}

    public function collection(): Collection
    {
        $query = Dusun::withCount([
            'warga' => fn ($q) => $q->where('status_kependudukan', 'hidup'),
            'warga as warga_baru_count' => fn ($q) => $q->whereBetween('created_at', ["{$this->dari} 00:00:00", "{$this->sampai} 23:59:59"]),
            'warga as kematian_count' => fn ($q) => $q->whereBetween('tanggal_meninggal', [$this->dari, $this->sampai]),
        ]);

        if ($this->dusunId) {
            $query->where('id', $this->dusunId);
        }

        return $query->orderBy('nama_dusun')->get();
    }

    public function headings(): array
    {
        return ["ARSIP PERIODE: {$this->labelPeriode}", '', '', ''];
    }

    public function map($dusun): array
    {
        return [
            $dusun->nama_dusun,
            'Total Warga Aktif: '.$dusun->warga_count,
            'Warga Baru Diinput Bulan Ini: '.$dusun->warga_baru_count,
            'Kematian Bulan Ini: '.$dusun->kematian_count,
        ];
    }

    public function title(): string
    {
        return 'Ringkasan per Dusun';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']], 'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '06381F'],
            ]],
        ];
    }
}

class KematianSheet implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(
        protected string $dari,
        protected string $sampai,
        protected ?int $dusunId = null,
    ) {}

    public function collection(): Collection
    {
        $query = Warga::with('dusun')->whereBetween('tanggal_meninggal', [$this->dari, $this->sampai]);

        if ($this->dusunId) {
            $query->where('dusun_id', $this->dusunId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'NIK', 'Dusun', 'Tanggal Meninggal'];
    }

    public function map($w): array
    {
        static $no = 0;
        $no++;

        return [$no, $w->nama, $w->nik, $w->dusun->nama_dusun ?? '-', ExcelFormat::tanggal($w->tanggal_meninggal)];
    }

    public function title(): string
    {
        return 'Data Kematian';
    }

    public function columnFormats(): array
    {
        return ['E' => 'dd-mm-yyyy'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F5E37'],
            ]],
        ];
    }
}
