<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecurityQuestion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'security_question';
    protected $primaryKey = 'sq_id';
    public $timestamps = false;
}
