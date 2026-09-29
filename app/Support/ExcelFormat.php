<?php

namespace App\Support;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as SpreadsheetDate;

/**
 * Sebelumnya semua tanggal di file export (mis. Tanggal Lahir, Tanggal Diinput ke
 * Sistem) ditulis sebagai teks biasa (format 'd-m-Y'/'d-m-Y H:i'). Akibatnya di
 * Excel kolom tersebut jadi bertipe "General"/Text, bukan "Date" - tidak bisa
 * diurutkan/difilter sebagai tanggal dan rata kiri (bukan rata kanan seperti
 * angka/tanggal asli).
 *
 * Helper ini mengubah tanggal menjadi nilai serial Excel asli, supaya kolomnya
 * benar-benar bertipe Date/Time saat dibuka di Excel. Dipakai bersama
 * WithColumnFormatting pada masing-masing kelas Export.
 */
class ExcelFormat
{
    /** Nilai tanggal (tanpa jam) untuk kolom bertipe Date. */
    public static function tanggal(?Carbon $date): ?float
    {
        if (! $date) {
            return null;
        }

        return floor(SpreadsheetDate::dateTimeToExcel($date));
    }

    /** Nilai jam (pecahan hari, 0-1) untuk kolom bertipe Time - dipakai terpisah dari tanggal. */
    public static function jam(?Carbon $date): ?float
    {
        if (! $date) {
            return null;
        }

        $full = SpreadsheetDate::dateTimeToExcel($date);

        return $full - floor($full);
    }

    /** Nilai tanggal+jam sekaligus (satu kolom Date & Time) bila memang ingin digabung. */
    public static function tanggalJam(?Carbon $date): ?float
    {
        if (! $date) {
            return null;
        }

        return SpreadsheetDate::dateTimeToExcel($date);
    }
}
