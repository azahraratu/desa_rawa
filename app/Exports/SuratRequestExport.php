<?php

namespace App\Exports;

use App\Models\SuratRequest;
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

class SuratRequestExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStyles, WithTitle
{
    private const FORMAT_TANGGAL = 'dd-mm-yyyy';

    private const FORMAT_JAM = 'hh:mm';

    public function __construct(
        protected ?int $dusunId = null,
        protected ?string $dari = null,
        protected ?string $sampai = null,
    ) {}

    public function collection(): Collection
    {
        $query = SuratRequest::with(['suratType', 'warga.dusun', 'pemohon'])->latest();

        if ($this->dusunId) {
            $query->whereHas('warga', fn ($q) => $q->where('dusun_id', $this->dusunId));
        }

        if ($this->dari && $this->sampai) {
            $query->whereBetween('created_at', ["{$this->dari} 00:00:00", "{$this->sampai} 23:59:59"]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Kode Permohonan', 'Nomor Surat', 'Jenis Surat', 'Atas Nama', 'NIK',
            'Dusun', 'Keperluan', 'Status', 'Diajukan Oleh Kadus?',
            'Tanggal Diajukan', 'Jam Diajukan', 'Tanggal Selesai', 'Jam Selesai',
        ];
    }

    public function map($surat): array
    {
        static $no = 0;
        $no++;

        $selesai = $surat->status === 'selesai' ? $surat->updated_at : null;

        return [
            $no,
            $surat->kode_permohonan,
            $surat->nomor_surat,
            $surat->suratType->nama ?? '-',
            $surat->warga->nama ?? '-',
            $surat->warga->nik ?? '-',
            $surat->warga->dusun->nama_dusun ?? '-',
            $surat->alasan,
            $surat->labelStatusSaatIni(),
            $surat->dibuat_oleh_kadus ? 'Ya' : 'Tidak',
            ExcelFormat::tanggal($surat->created_at),
            ExcelFormat::jam($surat->created_at),
            ExcelFormat::tanggal($selesai),
            ExcelFormat::jam($selesai),
        ];
    }

    public function title(): string
    {
        return 'Permohonan Surat';
    }

    public function columnFormats(): array
    {
        return [
            'K' => self::FORMAT_TANGGAL,
            'L' => self::FORMAT_JAM,
            'M' => self::FORMAT_TANGGAL,
            'N' => self::FORMAT_JAM,
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
