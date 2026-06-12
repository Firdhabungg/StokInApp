<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'toko_name' => ['required', 'string', 'max:255'],
            'toko_email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:tokos,email'],
            'toko_address' => ['required', 'string'],
            'toko_phone' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
            'toko_name.required' => 'Nama toko wajib diisi.',
            'toko_email.required' => 'Email toko wajib diisi.',
            'toko_email.email' => 'Format email toko tidak valid.',
            'toko_email.unique' => 'Email toko sudah terdaftar.',
            'toko_address.required' => 'Alamat toko wajib diisi.',
            'toko_phone.required' => 'Nomor telepon toko wajib diisi.'
        ];
    }
}
