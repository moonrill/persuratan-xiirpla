<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id_jenis_surat' => ['required'],
            'id_user' => ['required'],
            'tanggal_surat' => ['required', 'date'],
            'ringkasan' => ['nullable'],
            'file' => ['nullable', 'mimes:pdf']
        ];
    }

    public function attributes()
    {
        return [
            'id_jenis_surat' => 'Jenis surat',
            'id_user' => 'User'
        ];
    }
}
