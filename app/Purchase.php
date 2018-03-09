<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
	// https://laravel.com/docs/5.6/eloquent#inserting-and-updating-models
	// protected $table = 'purchase';
	public $timestamps = false;
	protected $fillable = ['Product', 'PackSize', 'UnitPrice', 'Qty', 'Payment'];
}
