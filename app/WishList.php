<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    public $timestamps = false;
	protected $fillable = ['ProductType', 'Qty'];
}
