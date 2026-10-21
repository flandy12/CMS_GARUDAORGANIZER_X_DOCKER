<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can("update project");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "banner"                         => ["required"],
            "wording"                        => ["nullable", "max:1000"],
            "project_titles"                 => ["required", "array"],
            "project_titles.*"               => ["required", "max:100"],
            "project_descriptions"           => ["required", "array"],
            "project_descriptions.*"         => ["required", "max:1000"],
            "project_images"                 => ["required", "array"],
        ];

        $data = $this->all();

        if(is_object($data["banner"])) {
            array_push($rules["banner"], "mimes:jpeg,jpg,png", "max:1000");
        }

        foreach ($data["project_images"] as $key => $image) {
            $rules["project_images.".$key] = ["required"];
            
            if(is_object($image)) {
                array_push($rules["project_images.".$key], "mimes:jpeg,jpg,png", "max:1000");
            }
        }

        return $rules;
    }
}
