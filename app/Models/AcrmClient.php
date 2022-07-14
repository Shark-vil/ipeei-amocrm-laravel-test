<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcrmClient extends Model
{
	use HasFactory;

	protected $fillable = [
		'accessToken',
		'refreshToken',
		'expires',
		'baseDomain',
	];
}
