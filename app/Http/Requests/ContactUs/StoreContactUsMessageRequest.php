<?php

namespace App\Http\Requests\ContactUs;

use App\Rules\PhoneNumber;
use App\Rules\PhoneNumberLength;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactUsMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "full_name"                 => ["required", "max:50"],
            "email"                     => ["required", "email:rfc,dns", "max:100"],
            "phone_number"              => ["required", "numeric", new PhoneNumber, new PhoneNumberLength],
            "company_name"              => ["required", "max:150"],
            "body_message"              => ["required", "max:2000"],
        ];
    }
}
