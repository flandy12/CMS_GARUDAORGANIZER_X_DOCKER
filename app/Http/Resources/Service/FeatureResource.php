<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        	"type"             => $this->type,
            "banner"            => asset($this->banner),
            "title"             => $this->title,
            "wording"           => $this->wording,
            "image"             => asset($this->image),
            "other_images"      => $this->other_image,
            "description"       => str_replace("\n", "<br />", $this->description),
        ];
    }
}
