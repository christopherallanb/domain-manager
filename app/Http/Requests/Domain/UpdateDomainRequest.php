<?php

namespace App\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDomainRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $domainId = $this->route('domain') ? $this->route('domain')->id : null;

        return [
            'name' => ['required','string','max:255', Rule::unique('domains','name')->ignore($domainId)],
            'registrar' => 'required|string|max:255',
            'expiration_date' => 'required|date',
            'annual_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'auto_renew' => 'nullable|boolean',
        ];
    }
}
