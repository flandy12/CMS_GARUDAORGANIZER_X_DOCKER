<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $images = [];

        if($this->other_image) {
            foreach ($this->other_image as $key => $value) {
                $images[] = [
                    "image"     => asset($value['image']),
                    "title"     => $value["title"],
                ];
            }
        }

        return [
        	"id"				=> encrypt($this->_id),
        	"type"			    => $this->type,
            "banner"            => asset($this->banner),
            "title"             => $this->title,
            "wording"           => $this->wording,
            "image"             => asset($this->image),
            "other_images"      => count($images) > 0 ? $images : null,
            "description"       => str_replace("\n", "<br />", $this->description),
        ];
    }
}
