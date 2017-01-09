<?php

namespace App\Api;
use App\HealthPortal;
use App\Shclinic;


class HealthPortalClient extends ApiClient{
    public function getData($uic, $hpid, $txn_date){
        $send_hp = HealthPortal::where('id', $hpid)->get()->first();
        $this_hp = Shclinic::all()->first();


        $this->url = $send_hp->ip_addr;
        // $this->url = 'http://localhost/';
        ////////////////////////////////////
        // ayusin mo yun ah  ///////////////
        ////////////////////////////////////
        $endpoint = 'api/portal/' . $uic . '/' . $this_hp->hp_id . '/' . $txn_date;

        $res = $this->get($endpoint);
        if ($res){
            //decrypt data using my pubkey
        }

        return $res;
    }
}