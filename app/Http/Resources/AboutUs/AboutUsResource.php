<?php

namespace App\Http\Resources\AboutUs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutUsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => encrypt($this->_id),
        	"banner"		=> $this->banner,
            "banner_wording"=> $this->banner_wording,
        	"wording"		=> $this->wording,
            "image"         => asset($this->image),
            "vision_mission"=> $this->vision_mission,
        ];
    }
}
