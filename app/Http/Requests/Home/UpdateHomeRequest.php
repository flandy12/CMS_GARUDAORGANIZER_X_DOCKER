<?php

namespace App\Http\Requests\Home;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can("update home");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "banner_titles"                 => ["required", "array"],
            "banner_titles.*"               => ["required", "max:100"],
            "banner_descriptions"           => ["required", "array"],
            "banner_descriptions.*"         => ["required", "max:800"],
            "banner_colors"                 => ["required", "array"],
            "banner_colors.*"               => ["required", "max:10"],
            "banner_images"                 => ["required", "array"],
            "service_name"                  => ["required", "array"],
            "service_name.*"                => ["required", "max:100"],
            "service_links"                 => ["required", "array"],
            "service_links.*"               => ["required", "string", "max:800"],
            "service_descriptions"          => ["required", "array"],
            "service_descriptions.*"        => ["required", "max:1000"],
            "service_images"                => ["required", "array"],
            "sector_name"                   => ["required", "array"],
            "sector_name.*"                 => ["required", "max:100"],
            "sector_descriptions"           => ["required", "array"],
            "sector_descriptions.*"         => ["required", "max:1000"],
            "sector_images"                 => ["required", "array"],
            "partner_logo"                  => ["required", "array"],
            "project_name"                  => ["required", "array"],
            "project_name.*"                => ["required", "string", "max:100"],
            "project_descriptions"          => ["required", "array"],
            "project_descriptions.*"        => ["required", "max:1000"],
            "project_images"                => ["required", "array"],
            "achievement_images"            => ["required", "array"],
            "achievement_numbers"           => ["required", "array"],
            "achievement_numbers.*"         => ["required", "numeric"],
            "achievement_name"              => ["required", "array"],
            "achievement_name.*"            => ["required", "max:100"],
            "testimonial_titles"            => ["required", "array"],
            "testimonial_titles.*"          => ["required", "max:100"],
            "testimonial_images"            => ["required", "array"],
            "testimonial_videos"            => ["required", "array"],
            "testimonial_videos.*"          => ["required"],
            "why_choose_us_descriptions"    => ["required", "array"],
            "why_choose_us_descriptions.*"  => ["required", "max:200"],
            "why_choose_us_images"          => ["required", "array"],
            "why_choose_us_other_images"    => ["required", "array", "min:2", "max:7"],
        ];

        $data = $this->all();

        foreach ($data["banner_images"] as $key => $image) {
            $rules["banner_images.".$key] = ["required"];
            
            if(is_object($image)) {
                array_push($rules["banner_images.".$key], "mimes:jpeg,jpg,png", "max:1000");
            }
        }

        foreach ($data["service_images"] as $key => $image) {
            $rules["service_images.".$key] = ["required"];

            if(is_object($image)) {
                array_push($rules["service_images.".$key], "mimes:jpeg,jpg,png", "max:1000");
            }
        }

        foreach ($data["sector_images"] as $key => $image) {
            $rules["sector_images.".$key] = ["required"];

            if(is_object($image)) {
                array_push($rules["sector_images.".$key], "mimes:jpeg,jpg,png", "max:1000");
            }
        }

        foreach ($data["partner_logo"] as $key => $image) {
            $rules["partner_logo.".$key] = ["required"];

            if(is_object($image)) {
                array_push($rules["partner_logo.".$key], "mimes:jpeg,jpg,png", "max:1000");
            }
        }

        foreach ($data["project_images"] as $key => $image) {
            $rules["project_images.".$key] = ["required"];

            if(is_object($image)) {
                array_push($rules["project_images.".$key], "mimes:jpeg,jpg,png", "max:1000");
            }
        }

        foreach ($data["achievement_images"] as $key => $image) {
            $rules["achievement_images.".$key] = ["required"];

            if(is_object($image)) {
                array_push($rules["achievement_images.".$key], "mimes:jpeg,jpg,png", "max:1000");
            }
        }

        foreach ($data["testimonial_images"] as $key => $image) {
            $rules["testimonial_images.".$key] = ["required"];

            if(is_object($image)) {
                array_push($rules["testimonial_images.".$key], "mimes:jpeg,jpg,png", "max:1000");
            }
        }

        foreach ($data["why_choose_us_images"] as $key => $image) {
            $rules["why_choose_us_images.".$key] = ["required"];

            if(is_object($image)) {
                array_push($rules["why_choose_us_images.".$key], "mimes:jpeg,jpg,png", "max:1000");
            }
        }

        if(isset($data["why_choose_us_other_images"])) {
            foreach ($data["why_choose_us_other_images"] as $key => $image) {
                $rules["why_choose_us_other_images.".$key] = ["required"];

                if(is_object($image)) {
                    array_push($rules["why_choose_us_other_images.".$key], "mimes:jpeg,jpg,png", "max:1000");
                }
            }
        }

        return $rules;
    }
}
