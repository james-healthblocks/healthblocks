<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    protected $table = 'text';
    protected $primaryKey = 'field_name, value, text';
    
    public $timestamps = false;
}
