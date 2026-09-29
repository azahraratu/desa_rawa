<?php

namespace App\Imports;

use App\Models\Warga;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

/**
 * Import data warga massal dari file Excel (.xlsx). Kolom pada file HARUS
 * mengikuti header di template resmi (lihat WargaImportTemplateExport):
 * NIK, No KK, Nama, Jenis Kelamin, Tempat Lahir, Tanggal Lahir, Alamat,
 * No HP, Status Ekonomi, Status Pernikahan, Status Pendidikan, Pekerjaan.
 *
 * Jika NIK sudah ada di dusun yang sama, datanya akan DIPERBARUI (bukan duplikat).
 */
class WargaImport implements SkipsOnError, SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    public int $berhasil = 0;

    public int $gagal = 0;

    /** @var array<int, string> */
    public array $pesanGagal = [];

    public function __construct(protected int $dusunId, protected int $kadusId) {}

    public function model(array $row)
    {
        $nik = $this->bersihkanDigit($row['nik'] ?? '');
        $noKk = $this->bersihkanDigit($row['no_kk'] ?? '');

        if (strlen($nik) !== 16 || strlen($noKk) !== 16) {
            $this->gagal++;
            $this->pesanGagal[] = "Baris dengan nama \"{$row['nama']}\": NIK/No KK harus 16 digit.";

            return null;
        }

        $statusEkonomi = in_array($row['status_ekonomi'] ?? null, ['mampu', 'kurang_mampu', 'tidak_mampu'])
            ? $row['status_ekonomi'] : 'mampu';
        $statusPernikahan = in_array($row['status_pernikahan'] ?? null, ['belum_menikah', 'menikah', 'cerai_hidup', 'cerai_mati'])
            ? $row['status_pernikahan'] : 'belum_menikah';
        $statusPendidikan = in_array($row['status_pendidikan'] ?? null, ['tidak_sekolah', 'sd', 'smp', 'sma', 'diploma', 's1', 's2', 's3'])
            ? $row['status_pendidikan'] : 'sd';
        $statusHubungan = in_array($row['status_hubungan_keluarga'] ?? null, array_keys(Warga::labelHubunganKeluarga()))
            ? $row['status_hubungan_keluarga'] : 'anak';

        $rtId = null;
        $namaRt = trim((string) ($row['rt'] ?? ''));
        if ($namaRt !== '') {
            $rt = \App\Models\Rt::firstOrCreate(
                ['dusun_id' => $this->dusunId, 'nama_rt' => $namaRt]
            );
            $rtId = $rt->id;
        }

        $payload = [
            'no_kk' => $noKk,
            'nama' => trim((string) ($row['nama'] ?? '')),
            'jenis_kelamin' => strtoupper(trim((string) ($row['jenis_kelamin'] ?? 'L'))) === 'P' ? 'P' : 'L',
            'status_hubungan_keluarga' => $statusHubungan,
            'rt_id' => $rtId,
            'tempat_lahir' => trim((string) ($row['tempat_lahir'] ?? '')),
            'nama_ayah' => trim((string) ($row['nama_ayah'] ?? '')) ?: null,
            'nama_ibu' => trim((string) ($row['nama_ibu'] ?? '')) ?: null,
            'tanggal_lahir' => $this->parseTanggal($row['tanggal_lahir'] ?? null),
            'alamat' => trim((string) ($row['alamat'] ?? '')),
            'no_hp' => trim((string) ($row['no_hp'] ?? '')),
            'dusun_id' => $this->dusunId,
            'status_ekonomi' => $statusEkonomi,
            'status_pernikahan' => $statusPernikahan,
            'status_pendidikan' => $statusPendidikan,
            'pekerjaan' => trim((string) ($row['pekerjaan'] ?? '')) ?: null,
            'status_kependudukan' => 'hidup',
            'diinput_oleh' => $this->kadusId,
        ];

        if (! $payload['tanggal_lahir']) {
            $this->gagal++;
            $this->pesanGagal[] = "Baris dengan nama \"{$payload['nama']}\": format Tanggal Lahir tidak valid (gunakan YYYY-MM-DD).";

            return null;
        }

        $existing = Warga::cariBerdasarkan('nik', $nik)->first();

        if ($existing) {
            $existing->update($payload);
            $this->berhasil++;

            return null;
        }

        $this->berhasil++;

        return new Warga($payload + ['nik' => $nik]);
    }

    public function rules(): array
    {
        return [
            'nik' => ['required'],
            'no_kk' => ['required'],
            'nama' => ['required', 'string'],
            'tanggal_lahir' => ['required'],
        ];
    }

    public function onError(\Throwable $e)
    {
        $this->gagal++;
        $this->pesanGagal[] = $e->getMessage();
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->gagal++;
            $this->pesanGagal[] = 'Baris '.$failure->row().': '.implode(', ', $failure->errors());
        }
    }

    protected function bersihkanDigit(mixed $value): string
    {
        // Menangani kasus NIK/No KK yang terbaca sebagai angka besar (notasi ilmiah) oleh Excel.
        if (is_float($value)) {
            $value = number_format($value, 0, '', '');
        }

        return preg_replace('/\D/', '', (string) $value);
    }

    protected function parseTanggal(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Excel kadang mengirim tanggal sebagai serial number.
        if (is_numeric($value)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }

        try {
            return \Carbon\Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
