<?php

namespace App\Http\Requests\Kadus;

use App\Models\Warga;
use App\Rules\UniqueEncrypted;
use Illuminate\Foundation\Http\FormRequest;

class StoreWargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isKadus() ?? false;
    }

    public function rules(): array
    {
        $wargaId = $this->route('warga')?->id;

        return [
            'nik' => ['required', 'digits:16', new UniqueEncrypted(Warga::class, 'nik', $wargaId)],
            'no_kk' => ['required', 'digits:16'],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'status_hubungan_keluarga' => ['required', 'in:kepala_keluarga,suami,istri,anak,menantu,cucu,orang_tua,mertua,famili_lain,lainnya'],
            'rt_id' => ['nullable', 'exists:rts,id,dusun_id,'.$this->user()->dusun_id],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'nama_ayah' => ['nullable', 'string', 'max:150'],
            'nama_ibu' => ['nullable', 'string', 'max:150'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+]*$/'],
            'status_ekonomi' => ['required', 'in:mampu,kurang_mampu,tidak_mampu'],
            'status_pernikahan' => ['required', 'in:belum_menikah,menikah,cerai_hidup,cerai_mati'],
            'status_pendidikan' => ['required', 'in:tidak_sekolah,sd,smp,sma,diploma,s1,s2,s3'],
            'pekerjaan' => ['required', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nik' => 'NIK',
            'no_kk' => 'Nomor KK',
            'status_hubungan_keluarga' => 'Status Hubungan dalam Keluarga',
            'nama_ayah' => 'Nama Ayah Kandung',
            'nama_ibu' => 'Nama Ibu Kandung',
            'pekerjaan' => 'Pekerjaan',
        ];
    }
}
