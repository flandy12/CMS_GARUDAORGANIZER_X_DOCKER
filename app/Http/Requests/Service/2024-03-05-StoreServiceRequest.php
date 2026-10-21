<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can("create service");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "type"                          => ["required", "array"],
            "type.*"                        => ["required", "max:100"],
            "banner"                        => ["required", "array"],
            "banner.*"                      => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "wording"                       => ["required", "array"],
            "wording.*"                     => ["required", "max:1000"],
            "service_descriptions"          => ["required", "array"],
            "service_descriptions.*"        => ["required", "max:1000"],
        ];
    }
}
