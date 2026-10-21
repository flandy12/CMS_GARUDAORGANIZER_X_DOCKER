<?php

namespace App\Http\Requests\SocialMedia;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can("update social media");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name"              => ["required", "max:30", "unique:social_media,name,{$this->socialMedia->_id}"],
            "link"              => ["required", "max:300"],
            "icon"              => ["required", "mimes:jpeg,jpg,png", "max:500"],
        ];
    }
}
