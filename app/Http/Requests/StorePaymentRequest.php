<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PaymentAmount;
use App\Models\Payment;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', [Payment::class, $this->transaction]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'paid_on' => 'required|date|date_format:Y-m-d H:i:s|before:'.now(),
            'amount'  => ['required', 'numeric', 'gt:0', new PaymentAmount($this->transaction)],
            'details' => 'nullable|max:300',
        ];
    }
}
