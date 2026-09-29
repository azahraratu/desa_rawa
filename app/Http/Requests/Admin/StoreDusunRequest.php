<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDusunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $dusunId = $this->route('dusun')?->id;

        return [
            'nama_dusun' => ['required', 'string', 'max:100', 'unique:dusuns,nama_dusun,'.($dusunId ?: 'NULL').',id'],
        ];
    }

    public function attributes(): array
    {
        return ['nama_dusun' => 'Nama Dusun'];
    }
}
