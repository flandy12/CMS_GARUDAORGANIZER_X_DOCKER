<?php

namespace App\Http\Requests\Home;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create home');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "banner_titles"                 => ["required", "array"],
            "banner_titles.*"               => ["required", "max:100"],
            "banner_descriptions"           => ["required", "array"],
            "banner_descriptions.*"         => ["required", "max:800"],
            "banner_images"                 => ["required", "array"],
            "banner_images.*"               => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "banner_colors"                 => ["required", "array"],
            "banner_colors.*"               => ["required", "max:10"],
            "service_name"                  => ["required", "array"],
            "service_name.*"                => ["required", "max:100"],
            "service_links"                 => ["required", "array"],
            "service_links.*"               => ["required", "string", "max:800"],
            "service_descriptions"          => ["required", "array"],
            "service_descriptions.*"        => ["required", "max:1000"],
            "service_images"                => ["required", "array"],
            "service_images.*"              => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "sector_name"                   => ["required", "array"],
            "sector_name.*"                 => ["required", "max:100"],
            "sector_descriptions"           => ["required", "array"],
            "sector_descriptions.*"         => ["required", "max:1000"],
            "sector_images"                 => ["required", "array"],
            "sector_images.*"               => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "partner_logo"                  => ["required", "array"],
            "partner_logo.*"                => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "project_name"                  => ["required", "array"],
            "project_name.*"                => ["required", "string", "max:100"],
            "project_descriptions"          => ["required", "array"],
            "project_descriptions.*"        => ["required", "max:1000"],
            "project_images"                => ["required", "array"],
            "project_images.*"              => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "achievement_images"            => ["required", "array"],
            "achievement_images.*"          => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "achievement_numbers"           => ["required", "array"],
            "achievement_numbers.*"         => ["required", "number"],
            "achievement_name"              => ["required", "array"],
            "achievement_name.*"            => ["required", "max:100"],
            "testimonial_titles"            => ["required", "array"],
            "testimonial_titles.*"          => ["required", "max:100"],
            "testimonial_images"            => ["required", "array"],
            "testimonial_images.*"          => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "testimonial_videos"            => ["required", "array"],
            "testimonial_videos.*"          => ["required"],
            "why_choose_us_descriptions"    => ["required", "array"],
            "why_choose_us_descriptions.*"  => ["required", "max:200"],
            "why_choose_us_images"          => ["required", "array"],
            "why_choose_us_images.*"        => ["required", "mimes:jpeg,jpg,png", "max:1000"],
            "why_choose_us_other_images"    => ["required", "array", "min:2", "max:7"],
            "why_choose_us_other_images.*"  => ["required", "mimes:jpeg,jpg,png", "max:1000"]
        ];
    }
}
