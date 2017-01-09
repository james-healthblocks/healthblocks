<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends SyncModel
{
	// use Traits\CompositePrimaryKey;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service';
    protected $uniqueFields = ['shc_id', 'sdate', 'service_type', 'client_type', 'venue', 'sex'];

    // protected $primaryKey = 'shc_id, client_type, sdate, service_type, venue, sex';
    protected $fillable = ['shc_id', 'id', 'sdate', 'service_type', 'client_type', 'venue', 'sex', 'count', 'invalid'];
    // public $timestamps = false;

    public function getID(){
        $data = new Service;

        foreach($this->uniqueFields as $field){
            $data = $data->where($field, $this->attributes[$field]);
        }

        $data = $data->value('id');
        
        return $data ? $data : null;
    }
}
