<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'npk' => ['required', 'alpha_num'],
            'password' => ['required', 'string'],
            'captcha'  => ['required', 'captcha'],
        ];
    }

    public function messages(): array
    {
        return [
            'captcha.required' => 'Captcha wajib diisi',
            'captcha.captcha'  => 'Captcha yang anda masukkan salah',
        ];
    }
}
