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
<?php

namespace App\Http\Requests\Domain;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDomainRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
