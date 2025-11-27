<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TravelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'destination_type' => ['required', 'string', 'in:station,planet,moon,stargate'],
            'destination_id' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'destination_type.required' => 'Debes especificar el tipo de destino.',
            'destination_type.in' => 'Tipo de destino inválido.',
            'destination_id.required' => 'Debes especificar un destino.',
            'destination_id.integer' => 'ID de destino inválido.',
        ];
    }
}
