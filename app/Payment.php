<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	public $timestamps = false;
	protected $fillable = ['Amount', 'Store', 'Date', 'PaymentType', 'Bank', 'Instalment', 'TrxCode'];
}
