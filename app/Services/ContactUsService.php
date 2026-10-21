<?php

namespace App\Services;

use App\Http\Resources\ContactUs\ContactUsResource;
use App\Library\Enums\Status;
use App\Mail\ContactUsMessageMail;
use App\Models\ContactUs;
use App\Models\ContactUsMessage;
use Illuminate\Support\Facades\Mail;

class ContactUsService {
	public function createContactUs(array $data): ContactUs
	{
		return ContactUs::create([
			"to_email"			=> $data["to_email"],
			"whatsapp_number"	=> isset($data["whatsapp_number"]) ? '62'.$data["whatsapp_number"] : null,
			"status"			=> Status::ACTIVE,
		]);
	}

	public function updateContactUs(array $data, contactUs $contactUs): void
	{
		$contactUs->update([
			"to_email"			=> $data["to_email"],
			"whatsapp_number"	=> isset($data["whatsapp_number"]) ? '62'.$data["whatsapp_number"] : null,
			"status"			=> Status::ACTIVE,
		]);
	}

	public function deleteContactUs(ContactUs $contactUs): void
	{
		$contactUs->delete();
	}

	public function createMessage(array $data, string $to_email): ContactUsMessage
	{
		return ContactUsMessage::create([
			"to_email"				=> $to_email,
			"full_name"				=> $data["full_name"],
			"email"					=> $data["email"],
			"phone_number"			=> $data["phone_number"],
			"company_name"			=> $data["company_name"],
			"message"				=> $data["body_message"],
		]);
	}

	public function sendEmail(array $data, string $to_email): void
	{
		Mail::to($to_email)->send(new ContactUsMessageMail($data));
	}

	public function getInformation():? ContactUsResource
	{
		$contact_us = ContactUs::where("status", Status::ACTIVE)->orderBy("created_at", "DESC")->first();

		return $contact_us ? new ContactUsResource($contact_us) : null;
	}
}