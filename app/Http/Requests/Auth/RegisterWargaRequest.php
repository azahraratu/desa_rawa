<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterWargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'digits:16'],
            'no_kk' => ['required', 'digits:16'],
            'no_hp' => ['required', 'string', 'min:9', 'max:20', 'regex:/^[0-9+]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'no_kk.digits' => 'Nomor KK harus terdiri dari 16 digit angka.',
            'no_hp.regex' => 'Nomor HP hanya boleh berisi angka.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nik' => 'NIK',
            'no_kk' => 'Nomor KK',
            'no_hp' => 'Nomor HP',
        ];
    }
}
