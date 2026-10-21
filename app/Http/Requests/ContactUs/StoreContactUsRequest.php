<?php

namespace App\Http\Requests\ContactUs;

use App\Rules\PhoneNumber;
use App\Rules\PhoneNumberLength;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactUsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can("create contact us");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "to_email"                  => ["required", "max:100", "email:rfc,dns"],
            "whatsapp_number"           => ["nullable", "numeric", new PhoneNumber, new PhoneNumberLength],
        ];
    }
}
