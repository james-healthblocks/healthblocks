<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Duplicate extends Model
{
    public $timestamps = false;
    public $fillable = [
        'newest_version', 'client_id', 'duplicate', 'reason'
    ];
    private $records = null;


    public static function sameUIC($records){
        $result = [];
        if ($records){
            $r = $records->groupBy('uic');
            $r = $r->filter(function ($value, $key){
                return count($value) > 1;
            });
            $r = $r->toArray();
            foreach($r as $key => $value){
                $result['Same UIC: ' . $key] = $value;
            }
        }
        return $result;
    }

    public static function sameName($records){
        $result = [];
        if ($records){
            $r = $records->groupBy(function ($value, $key){
                return $value['firstname'] . ' ' . $value['lastname'];
            });

            $r = $r->filter(function ($value, $key){
                return count($value) >1;
            });

            $r = $r->toArray();
            foreach($r as $key => $value){
                $result['Same Name: ' . $key] = $value;
            }
        }
        return $result;
    }

    public static function filterFalse($result){
        $not_duplicate = Duplicate::select('client_id', 'reason')->where('duplicate', false)->get()->groupBy('reason')->toArray();
        foreach($result as $key=>$value){
            if (in_array($key, array_keys($not_duplicate))){
                foreach($value as $key2=>$value2){
                    if (in_array($value2['client_id'], array_column($not_duplicate[$key], 'client_id'))){
                        unset($result[$key][$key2]);
                        if ($result[$key] == []){
                            unset($result[$key]);
                        }
                    }
                }
            }
        }
        return $result;
    }
    public static function getPotentialDuplicates(){
        $records = Icr::getAllClients();
        $result = [];

        $result = array_merge($result, Duplicate::sameUIC($records));
        $result = array_merge($result, Duplicate::sameName($records));

        $result = Duplicate::filterFalse($result);
        return $result;
    }
}
