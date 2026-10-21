<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $connection = "mongodb";
    protected $collection = "services";
    protected $fillable = ["type", "banner", "wording", "title", "description", "image", "other_image", "status"];
    protected $dates = ["created_at", "updated_at", "deleted_at"];
}
