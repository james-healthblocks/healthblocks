<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merged extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merged';
    protected $primaryKey = 'central_id, client_id';
    public $timestamps = false;
}
