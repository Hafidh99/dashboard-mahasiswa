<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Nama' => ['required', 'string', 'max:100'],
            'Email' => ['required', 'string', 'max:100', Rule::unique(User::class, 'Email')->ignore($this->user()->MhswID, 'MhswID')],
            'TempatLahir' => ['nullable', 'string', 'max:50'],
            'TanggalLahir' => ['nullable', 'date'],
            'Handphone' => ['nullable', 'string', 'max:50'],
            'Alamat' => ['nullable', 'string'],
            'NamaAyah' => ['nullable', 'string', 'max:50'],
            'NamaIbu' => ['nullable', 'string', 'max:50'],
            'AlamatOrtu' => ['nullable', 'string'],
        ];
    }
}
