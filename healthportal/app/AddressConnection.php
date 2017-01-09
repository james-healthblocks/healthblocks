<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressConnection extends Model
{
    public $timestamps = false;
    public $fillable = ['city', 'region', 'province'];
}
