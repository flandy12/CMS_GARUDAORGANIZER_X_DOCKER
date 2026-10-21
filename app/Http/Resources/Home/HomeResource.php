<?php

namespace App\Http\Resources\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                         => encrypt($this->_id),
        	"banner"		             => $this->banner,
        	"service"		             => $this->service,
        	"sector"		             => $this->sector,
        	"partner"		             => $this->partner,
        	"project"		             => $this->project,
        	"achievement"	             => $this->achievement,
        	"testimonial"	             => $this->testimonial,
            "why_choose_us"              => $this->why_choose_us,
            "why_choose_us_other_images" => $this->why_choose_us_other_image,
        ];
    }
}
