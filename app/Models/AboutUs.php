<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class AboutUs extends Model
{
    use SoftDeletes;

    protected $connection = "mongodb";
    protected $collection = "about_us";
    protected $fillable = ["banner", "banner_wording", "wording", "image", "vision_mission", "status"];
    protected $dates = ["created_at", "updated_at", "deleted_at"];
}
