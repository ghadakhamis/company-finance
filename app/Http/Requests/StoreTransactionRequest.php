<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'          => 'required|exists:users,id,deleted_at,NULL',
            'due_on'           => 'required|date|date_format:Y-m-d H:i:s',
            'amount'           => 'required|numeric|gt:0|lt:99999999',
            'vat'              => 'required|numeric|gte:0|lt:100',
            'is_vat_inclusive' => 'required|boolean',
        ];
    }
}
