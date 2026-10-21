<?php

namespace App\Http\Resources\ContactUs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        	"id"				=> encrypt($this->_id),
        	"to_email"			=> $this->to_email,
        	"whatsapp_number"	=> $this->whatsapp_number,
        ];
    }
}
