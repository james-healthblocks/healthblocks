<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Text;
use App\Shclinic;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function get_if_exists($arr, $key, $default){
        return (isset($arr[$key]) ? $arr[$key] : $default);
    }

    public function getAddrDropdowns(){
        $x = [
            'cities' => Text::where('field_name', 'city')->select('value', 'text')->get(),
            'provinces' => Text::where('field_name', 'province')->select('value', 'text')->get(),
            'regions' => Text::where('field_name', 'region')->select('value', 'text')->get()
        ];

        $r = array();
        foreach($x as $key=>$value){
            $r[$key] = array();
            foreach($value as $entry){
                $r[$key][$entry->value] = $entry->text;
            }
        }
        $r['data'] = '';
        $r['errors'] = '';
        return $r;
    }

    public function getRiskGroups(){
        $risk_groups = Text::select('value', 'text')->where('field_name', 'risk_group')->get()->keyBy('value')->toArray();
        unset($risk_groups['rg_no_known']);
        unset($risk_groups['rg_others']);

        $risk_groups['rg_partner']['restriction'] = 'female-only';
        $risk_groups['rg_msm']['restriction'] = 'male-only';

        return $risk_groups;
    }

    public function getClientTypes(){
        $clients = Text::select('value', 'text')->where('field_name', 'client_type')->orderBy(DB::raw('CAST(value as unsigned)', 'asc'))->get()->toArray();
        return $clients;
    }

    public function getServiceTypes(){
        $services = Text::select('value', 'text')->where('field_name', 'service_type')->get()->toArray();
        return $services;
    }
    public function clientRestrictions($sex){
        //$sex = 'male' or 'female'
        $a = Text::where('field_name', 'client_type_restriction')->where('value', $sex)->pluck('text')->toArray();
        return $a;
    }

    public function getContext(){
        $context = $this->getAddrDropdowns();
        $context['shc'] = Shclinic::all()->first();
        $context['risk_groups'] = $this->getRiskGroups();
        
        return $context;
    }
}
