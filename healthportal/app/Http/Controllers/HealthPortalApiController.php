<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Icr;
use App\Api\HealthNetworkClient;
use App\Shclinic;

class HealthPortalApiController extends Controller
{
    public function record(Request $request, $uic, $hpid, $txn_date){
        $hnc = new HealthNetworkClient;
        $shc = Shclinic::all()->first();

        if (!$hnc->checkAccess($uic, $shc->hp_id, $hpid)){
            abort(403);
        }
        // send encrypted data
        $icr = Icr::where([
            ['uic', '=', $uic],
            ['consult_date', '=', date('Y-m-d', $txn_date)]
        ])->get()->first();

        return response()->json([
            'uic' => $uic,
            'hpid' => $hpid,
            'txn_date' => $txn_date,
            'icr' => $icr
        ]);
    }
}
