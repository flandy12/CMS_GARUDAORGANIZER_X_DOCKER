<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class ContactUsMessage extends Model
{
	use SoftDeletes;

	protected $connection = "mongodb";
    protected $collection = "contact_us_message";
    protected $fillable = ["to_email", "full_name", "email", "phone_number", "company_name", "message"];
    protected $dates = ["created_at", "updated_at", "deleted_at"];
}
