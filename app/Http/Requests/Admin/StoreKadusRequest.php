<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Rules\UniqueEncrypted;
use Illuminate\Foundation\Http\FormRequest;

class StoreKadusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'nik' => ['required', 'digits:16', new UniqueEncrypted(User::class, 'nik')],
            'no_hp' => ['required', 'string', 'max:20', 'regex:/^[0-9+]+$/'],
            'dusun_id' => ['required', 'exists:dusuns,id', 'unique:users,dusun_id,NULL,id,role,kadus'],
        ];
    }

    public function messages(): array
    {
        return [
            'dusun_id.unique' => 'Dusun ini sudah memiliki akun Kepala Dusun aktif.',
        ];
    }

    public function attributes(): array
    {
        return ['nik' => 'NIK', 'dusun_id' => 'Dusun'];
    }
}
