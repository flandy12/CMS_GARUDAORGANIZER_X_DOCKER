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
            "type"                          => ["required", "max:150"],
            "banner"                        => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "wording"                       => ["nullable", "max:10000"],
            "service_title"                 => ["required", "max:100"],
            "service_description"           => ["required", "max:1000"],
            "service_image"                 => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "other_images"                  => ["nullable", "array"],
            "other_images.*"                => ["nullable", "mimes:jpeg,jpg,png", "max:1000"],
            "other_image_titles"            => ["nullable", "array"],
            "other_image_titles.*"          => ["nullable", "max:100"],
        ];
    }
}
