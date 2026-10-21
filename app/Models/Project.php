<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Project extends Model
{
	use SoftDeletes;

	protected $connection = "mongodb";
	protected $collection = "projects";
	protected $fillable = ["banner", "wording", "results", "status"];
	protected $dates = ["created_at", "updated_at", "deleted_at"];
}
