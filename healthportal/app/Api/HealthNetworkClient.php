<?php

namespace App\Api;
use App\HealthPortal;


class HealthNetworkClient extends ApiClient{
    // protected $url = '';
    protected $url = 'http://54.254.135.23:5000/';

    public function search($uic){
        $endpoint = 'search/record';

        $res = $this->post($endpoint, ['UIC' => $uic]);

        $res = $res['response'];
        if (array_key_exists('results', $res)){
            $results = $res['results'];
        } else {
            $results = [];
        }
        // $results = [
        //     [
        //         'uic' => $uic,
        //         'hpid' => '00001',
        //         'txn_date' => 1483587007,
        //         'remarks' => 'remark'
        //     ],
        //     [
        //         'uic' => $uic,
        //         'hpid' => '00001',
        //         'txn_date' => 1483587008,
        //         'remarks' => 'remark'
        //     ],
        //     [
        //         'uic' => $uic,
        //         'hpid' => '00002',
        //         'txn_date' => 1483587009,
        //         'remarks' => 'remark'
        //     ],
        //     [
        //         'uic' => $uic,
        //         'hpid' => '00002',
        //         'txn_date' => 1483587010,
        //         'remarks' => 'remark'
        //     ],
        //     [
        //         'uic' => $uic,
        //         'hpid' => '00003',
        //         'txn_date' => 1483587011,
        //         'remarks' => 'remark'
        //     ],
        //     [
        //         'uic' => $uic,
        //         'hpid' => '00003',
        //         'txn_date' => 1483587012,
        //         'remarks' => 'remark'
        //     ],
        // ];

        $r = [];
        foreach($results as $result){
            if (!array_key_exists($result['hpid'], $r)){
                $name = HealthPortal::find($result['hpid']);
                $name = $name ? $name->name : $result['hpid'];
                $r[$result['hpid']] = [];
                $r[$result['hpid']]['name'] = $name;
            }
            $r[$result['hpid']]['info'][] = [
                'txn_date' => $result['txn_date'],
                'remarks' => $result['remarks'],
                'uic' => $result['uic'],
            ];
        }

        return $r;
    }

    public function requestAccess($uic, $recv_hpid, $send_hpid){
        // get my pubkey

        $data = [
            'UIC' => $uic,
            'recv_hpid' => $recv_hpid,
            'recv_hpid_pubkey' => '1234',
            'send_hpid' => $send_hpid
        ];
        $res = $this->post('add/request/hp', $data);
        return $res;
        // return $this->post('add/request/hp', $data);
    }

    public function checkAccess($uic, $send_hpid, $recv_hpid){
        $data = [
            'UIC' => $uic,
            'send_hpid' => $send_hpid
        ];

        $res = $this->post('search/request', $data);

        if (array_key_exists('results', $res['response'])){
            foreach($res['response']['results'] as $result){
                if (($result['recv_hpid'] == $recv_hpid) && ($result['recv_hpid_pubkey'] != null) && $result['approve_status'] == "TRUE"){
                    return $result['recv_hpid_pubkey'];
                }
            }
        }
        return true;  // la pa ata access
        // return false;
    }

    public function register($hpid){
        $endpoint = 'register/healthportal';

        $res = $this->post(
            $endpoint,
            [
                'HPid'=>$hpid
            ]
        );
        $res = $res['response'];
        return array_key_exists('wallet_address', $res) ? $res['wallet_address'] : 'Sorry man';
    }

    public function createTransaction($uic, $hpid, $remarks){
        $res = $this->post(
            'add/record',
            [
                'UIC' => $uic,
                'HPid' => $hpid,
                'remarks' => $remarks
            ]
        );
        return $res['response'];
    }
}