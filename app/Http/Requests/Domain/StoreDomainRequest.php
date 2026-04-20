<?php

namespace App\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomainRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:domains,name|max:255',
            'registrar' => 'required|string|max:255',
            'expiration_date' => 'required|date',
            'annual_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'auto_renew' => 'nullable|boolean',
        ];
    }
}
