<?php

namespace App;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

use Illuminate\Database\Eloquent\Model;


class Shclinic extends Model
{
    use Traits\FileUpload;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shclinic';
    protected $primaryKey = 'shc_id';
    public $timestamps = false;

    protected $guarded = ['shc_id', 'validated', 'usrimage'];

}
