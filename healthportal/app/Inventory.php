<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Counter;

class Inventory extends SyncModel
{
	// use Traits\CompositePrimaryKey;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventory';
    // protected $primaryKey = 'shc_id, item_name, month, year, expiry_date';

    protected $uniqueFields = ['shc_id', 'item_name', 'month', 'year', 'expiry_date'];
    protected $fillable = ['shc_id', 'month', 'year', 'item_name', 'category', 'expiry_date', 'batchno', 'procuredcount', 'distributedcount', 'remarks', 'start_amt'];
    // public $timestamps = false;
    
    public function add($column, $amount = 1){
        $data = new Inventory;

        foreach($this->uniqueFields as $field){
            $data = $data->where($field, $this->attributes[$field]);
        }

        $id = $data->newQuery();
        $id = $id->value('id');
        $data = $data->value($column);

        if(is_null($id)){
            return false;
        }

        $newModel = Inventory::find($id);
        $newModel->{$column} = $data + $amount;

        return $newModel;
    }
}
