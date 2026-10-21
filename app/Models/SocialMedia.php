<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class SocialMedia extends Model
{
    use SoftDeletes;

    protected $connection = "mongodb";
    protected $collection = "social_media";
    protected $fillable = ["name", "link", "icon", "status"];
    protected $dates = ["created_at", "updated_at", "deleted_at"];
}
