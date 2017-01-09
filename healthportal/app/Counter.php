<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $table = 'counters';
    protected $primaryKey = 'counter_id';
    public $timestamps = false;
}
