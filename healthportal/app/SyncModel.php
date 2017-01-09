<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use App\Counter;


function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}


class SyncModel extends Model
{
    protected $rules = array();
    public $errors = null;
    private function createGUID(){
        $guid = '';
        $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code .= 'abcdefghijklmnopqrstuvwxyz';
        $code .= '0123456789';
        $max = strlen($code);

        for ($i=0; $i < 25; $i++){
            $guid .= $code[crypto_rand_secure(0, $max)];
        }
        return $guid;
    }

    public function save(array $options=array()){
        // create guid if none exists
        if (empty($this->attributes['guid'])){
            $this->attributes['guid'] = $this->createGUID();
        }

        // update counter and take value

        $counter = Counter::find('counter');
        if (!$counter){
            $counter = new Counter;
            $counter->counter_id='counter';
            $counter->value = 0;
        }
        $this->attributes['shc_id'] = $this->shc_id = 0;
        $counter->value += 1;
        $counter->save();
        $this->attributes['counter_last_update'] = $counter->value;
        return parent::save($options);
    }

    public function validate($data){
        $v = Validator::make($data, $this->rules);

        if ($v->fails()){
            $this->errors->merge($v->errors());
            return false;
        }

        return true;
    }

    public static function findComposite($keys){
        $query = null;
        foreach($keys as $key=>$value){
            if (!$query){
                $query = self::where($key, $value);
            } else {
                $query = $query->where($key, $value);
            }
        }
        return $query->first();
    }
}