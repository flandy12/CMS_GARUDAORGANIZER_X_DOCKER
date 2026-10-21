<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can("update service");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "type"                          => ["required", "max:150"],
            "banner"                        => ["required"],
            "wording"                       => ["nullable", "max:10000"],
            "service_title"                 => ["required", "max:100"],
            "service_image"                 => ["required"],
            "service_description"           => ["required"],
            "other_images"                  => ["nullable"],
            "other_image_titles"            => ["nullable", "array"],
            "other_image_titles.*"          => ["nullable", "max:100"],
        ];

        $data = $this->all();

        if(is_object($data["banner"])) {
            array_push($rules["banner"], "mimes:jpeg,jpg,png", "max:1000");
        }

        if(is_object($data["service_image"])) {
            array_push($rules["service_image"], "mimes:jpeg,jpg,png", "max:1000");
        }

        if(isset($data["other_images"])) {
            foreach ($data["other_images"] as $key => $image) {
                $rules["other_images.".$key] = ["required"];
                
                if(is_object($image)) {
                    array_push($rules["other_images.".$key], "mimes:jpeg,jpg,png", "max:1000");
                }
            }
        }

        return $rules;
    }
}
