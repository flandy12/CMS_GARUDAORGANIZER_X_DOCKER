<?php

namespace App\Http\Requests\AboutUs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutUsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can("update about us");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "banner"                        => ["required"],
            "banner_wording"                => ["nullable", "max:300"],
            "wording"                       => ["required", "max:1000"],
            "image"                         => ["required"],
            "vision_mission"                => ["required", "max:2000"]
        ];

        $data = $this->all();

        if(is_object($data["banner"])) {
            array_push($rules["banner"], "mimes:jpeg,jpg,png", "max:1000");
        }

        if(is_object($data["image"])) {
            array_push($rules["image"], "mimes:jpeg,jpg,png", "max:1000");
        }

        return $rules;
    }
}
