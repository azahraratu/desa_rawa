<?php

namespace App\Http\Requests\Kadus;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isKadus() ?? false;
    }

    public function rules(): array
    {
        $rtId = $this->route('rt')?->id;
        $dusunId = Auth::user()->dusun_id;

        return [
            'nama_rt' => [
                'required', 'string', 'max:20',
                'unique:rts,nama_rt,'.($rtId ?: 'NULL').',id,dusun_id,'.$dusunId,
            ],
            'ketua_rt' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function attributes(): array
    {
        return ['nama_rt' => 'Nama/Nomor RT', 'ketua_rt' => 'Ketua RT'];
    }
}
