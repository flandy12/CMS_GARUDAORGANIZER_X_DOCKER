<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can("create project");
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
            "wording"                       => ["nullable", "max:1000"],
            "project_titles"                => ["required", "array"],
            "project_titles.*"              => ["required", "max:100"],
            "project_descriptions"          => ["required", "array"],
            "project_descriptions.*"        => ["required", "max:1000"],
            "project_images"                => ["required", "array"],
            "project_images.*"              => ["required", "mimes:jpeg,jpg,png", "max:500"]
        ];
    }
}
