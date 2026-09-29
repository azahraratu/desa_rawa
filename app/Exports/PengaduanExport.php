<?php

namespace App\Exports;

use App\Models\Pengaduan;
use App\Support\ExcelFormat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengaduanExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStyles, WithTitle
{
    private const FORMAT_TANGGAL = 'dd-mm-yyyy';

    private const FORMAT_JAM = 'hh:mm';

    public function __construct(
        protected ?string $dari = null,
        protected ?string $sampai = null,
    ) {}

    public function collection(): Collection
    {
        $query = Pengaduan::with('pelapor')->latest();

        if ($this->dari && $this->sampai) {
            $query->whereBetween('created_at', ["{$this->dari} 00:00:00", "{$this->sampai} 23:59:59"]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['No', 'Judul', 'Kategori', 'Deskripsi', 'Pelapor', 'Status', 'Dilaporkan Kadus?', 'Tanggal Lapor', 'Jam Lapor'];
    }

    public function map($p): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $p->judul,
            ucfirst($p->kategori),
            $p->deskripsi,
            $p->pelapor->name ?? '-',
            ucfirst($p->status),
            $p->dilaporkan_oleh_kadus ? 'Ya' : 'Tidak',
            ExcelFormat::tanggal($p->created_at),
            ExcelFormat::jam($p->created_at),
        ];
    }

    public function title(): string
    {
        return 'Pengaduan';
    }

    public function columnFormats(): array
    {
        return [
            'H' => self::FORMAT_TANGGAL,
            'I' => self::FORMAT_JAM,
        ];
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
