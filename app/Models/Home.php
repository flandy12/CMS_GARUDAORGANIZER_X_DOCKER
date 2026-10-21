<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Home extends Model
{
	use SoftDeletes;

	protected $connection = "mongodb";
	protected $collection = "homes";
	protected $fillable = ["banner", "service", "sector", "partner", "project", "achievement", "testimonial", "why_choose_us", "why_choose_us_image", "why_choose_us_other_image", "status"];
	protected $dates = ["created_at", "updated_at", "deleted_at"];
}
