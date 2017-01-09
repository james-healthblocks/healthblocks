<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\HealthNetworkClient;
use App\Api\HealthPortalClient;
use App\HealthPortal;
use App\Shclinic;
use App\Icr;


class HealthNetworkController extends Controller
{
    public function portal(Request $request, $uic, $hpid){
        $context = $this->getContext();
        $hnc = new HealthNetworkClient;
        $clinic = Shclinic::all()->first();

        $hnc->requestAccess($uic, $clinic->hp_id, $hpid);
        $results = $hnc->search($uic);
        $results = array_key_exists($hpid, $results) ? $results[$hpid] : [];
        $context['results'] = $results['info'];
        $context['uic'] = $uic;
        $context['hpname'] = $results['name'];
        $context['hpid'] = $hpid;
        return view('health_network.portal', $context);
    }

    public function record(Request $request, $uic, $hpid, $txn_date){
        $context = $this->getContext();
        $hpc = new HealthPortalClient;
        $data = $hpc->getData($uic, $hpid, $txn_date);
        if ($data['body']['icr'] == NULL){
            abort(404);
        }
        $icr = new Icr;
        $icr->fill($data['body']['icr']);
        $context['icr'] = $icr;
        $context['portal'] = true;
        return view('client.form', $context);
    }
}
