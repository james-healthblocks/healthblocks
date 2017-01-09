<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reached extends SyncModel
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reached';
    protected $primaryKey = 'uic, date_reached';
}
