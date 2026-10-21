<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use SoftDeletes;

    protected $connection = "mongodb";
    protected $collection = "contact_us";
    protected $fillable = ["to_email", "whatsapp_number", "status"];
    protected $dates = ["created_at", "updated_at", "deleted_at"];
}
