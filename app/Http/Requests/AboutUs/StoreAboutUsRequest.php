<?php

namespace App\Http\Requests\AboutUs;

use Illuminate\Foundation\Http\FormRequest;

class StoreAboutUsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can("create about us");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "banner"                        => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "banner_wording"                => ["nullable", "max:300"],
            "wording"                       => ["required", "max:1000"],
            "image"                         => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "vision_mission"                => ["required", "max:2000"]
        ];
    }
}
