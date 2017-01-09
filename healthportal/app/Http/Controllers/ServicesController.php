<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Service;
use App\Text;
use DB;
use DateTime;

class ServicesController extends Controller
{
    public function encode(){
        $service_type = Text::where('field_name', 'service_type')->pluck('text')->toArray();
        $sex = Text::select('value', 'text')->where('field_name', 'sex')->get();
        $clients = $this->getClientTypes();

        $tg_fields = Text::select('value', 'text')->where('field_name', 'tg')->get()->toArray();

        $context["tg"] = [];
        foreach($tg_fields as $x) {
            $context["tg"][$x["value"]] = $x["text"];
        }

        $context["service_type"] = [];
        $context["client_type"] = [];
        $context["service_venue"] = [];

        //Sex specific client types
        $male_only = $this->clientRestrictions("male");
        $female_only = $this->clientRestrictions("female");

        foreach($sex as $x){
            $context["sex"][$x['value']] = $x["text"];
        }

        $venue = Service::select('venue')->distinct()->pluck('venue');
        $venueData = [];

        foreach($venue as $i=>$value){
            $row["value"] = $value;
            $row["text"] = $value;

            $venueData[$i] = $row;
        }

    $context["service_venue"] = $venueData;

        foreach($clients as $client){
            $row = [];
            $row["sex"] = [];

            if(in_array($client['value'], $male_only)){
                $row["sex"] = [array_search('Male', $context["sex"])];
            }else if(in_array($client['value'], $female_only)){
                $row["sex"] = [array_search('Female', $context["sex"])];
            }else{
                foreach ($context["sex"] as $i => $sex) {
                    array_push($row["sex"], $i);
                }
            }

            $row["label"] = $client['text'];
            $row["selected"] = false;

            array_push($context["client_type"], $row);

        }

        foreach($service_type as $service){
            $row = [];
            $row["label"] = $service;
            $row["show"] = false;

            array_push($context["service_type"], $row);
        }

        return view('services.encode', $context);
    }

    public function save(Request $request){
        
    	$data = json_decode($request->getContent(), true);
        $happiness = [];

    	foreach($data as $cell){
            $success = true;
            if(!is_null($cell["count"])){
                $service = new Service;
                
                $cell["shc_id"] = 0;
                $cell["sdate"] = date("Y-m-d", strtotime($cell["sdate"]));

                $service = $service->fill($cell);
                $serviceID = clone $service;

                $serviceID = $serviceID->getID();

                if(!is_null($serviceID)){
                    $newService = Service::find($serviceID);
                    $newService->count = $cell["count"];
                    $success = $newService->save();
                }else{
                    if($cell["count"] == 0){
                        continue;
                    }

                    $success = $service->save();
                } 

                if($success){
                    array_push($happiness, $cell);
                }
            }
        }

    	return response()->json([$happiness]);
    }

    public function reports(){
        $context = $this->getAddrDropdowns();
        return view('services.reports', $context);
    }

    public function displayReport(Request $request){
        $address = $this->getAddrDropdowns();

        $context["info"]["municipality"] = $address["cities"][$request->get("municipality")];
        $context["info"]["province"] = $address["provinces"][$request->get("province")];
        $context["info"]["region"] = $address["regions"][$request->get("region")];

        $context["info"]["startDate"] = new DateTime($request->get("activity-date-start"));
        $context["info"]["endDate"] = new DateTime($request->get("activity-date-end"));

        $startDate = $context["info"]["startDate"]->format("Y-m-d");
        $endDate = $context["info"]["endDate"]->format("Y-m-d");

        $context["info"]["startDate"] = $context["info"]["startDate"]->format("d F Y");
        $context["info"]["endDate"] = $context["info"]["endDate"]->format("d F Y");

        $services = $this->getServiceTypes();
        $clients = $this->getClientTypes();

        //Sex specific client types
        $male_only = $this->clientRestrictions("male");
        $female_only = $this->clientRestrictions("female");
        $sexes = Text::where('field_name', 'sex')->pluck('value')->toArray();

        $encounters = [];
        $encountersByPop = [];

        $clientTypes = [];

        foreach($services as $service){
            $encData = []; //Encounters Table

            $total["1"]["count"] = 0;
            $total["2"]["count"] = 0;

            $serviceQuery = new Service;
            //Number of Activities
            $serviceQuery = $serviceQuery->whereBetween('sdate', [$startDate, $endDate])
                                    ->where('service_type', $service["value"]);


            //Total
            $encData["count"] = $serviceQuery->count();

            $encData["label"] = $service["text"];
            array_push($encounters, $encData);

            foreach($clients as $client){
                foreach($sexes as $sex){
                    $popCount = 0;
                    if(!(($sex == 1 && in_array($client["value"], $female_only)) || ($sex ==2 && in_array($client["value"], $male_only)))){
                        $popCount = Service::select(DB::raw('SUM(count) as totalCount'))
                                    ->whereBetween('sdate', [$startDate, $endDate])
                                    ->where('service_type', $service["value"])
                                    ->where('client_type', $client["value"])
                                    ->where('sex', $sex)
                                    ->value("totalCount");

                        if($popCount == '')
                            $popCount = 0;

                        $encountersByPop[$service["text"]][$client["value"]][$sex]["count"] = $popCount;
                        $total[$sex]["count"] += $popCount;
                    }
                }

            }

            $encountersByPop[$service["text"]]["total"] = $total;
        }

        foreach($clients as $client){
            $buf = [];
            $buf["label"] = $client["text"];
            $buf["sex"] = [];

            foreach($sexes as $sex){
                if(!(($sex == 1 && in_array($client["value"], $female_only)) || ($sex ==2 && in_array($client["value"], $male_only)))){
                    array_push($buf["sex"], $sex);
                }
            }

            array_push($clientTypes, $buf);
        }

        $context["data"]["encounters"] = $encounters;
        $context["data"]["encountersByPop"] = $encountersByPop;

        $context["data"]["clients"] = $clientTypes;
        $context["info"]["colCount"] = count($clients)*2 + 2;

        $context["info"]["encActiviesNoColCount"] = ceil(($context["info"]["colCount"])/2 - 2) + 1;
        $number = $context["info"]["encActiviesNoColCount"];
        $context["info"]["encActiviesColCount"] = ( ($number%2  == 0) ? $number : $number-1 ); 
        
        return view('services.displayreport', $context);
    }

    public function database(){
        $context["page"] = 'services-db';

        $months = Service::select(DB::raw('DISTINCT MONTH(sdate) as months'))->pluck('months')->toArray();
        $years = Service::select(DB::raw('DISTINCT YEAR(sdate) as years'))->pluck('years')->toArray();

        $sex = Text::select('value', 'text')->where('field_name', 'sex')->get();
        $service_types = $this->getServiceTypes();
        $client_types = $this->getClientTypes();

        $venue = Service::select('venue')->distinct()->pluck('venue');
        $venueData = [];

        foreach($venue as $i=>$value){
            $row["value"] = $value;
            $row["text"] = $value;

            $venueData[$i] = $row;
        }


        foreach($months as $i => $month){
            $monthObj = DateTime::createFromFormat('!m', $month);
            $row['value'] = $month;
            $row['text'] = $monthObj->format('F');

            $months[$i] = $row;
        }

        foreach($years as $i=>$year){
            $row['value'] = $year;
            $row['text'] = $year;

            $years[$i] = $row;
        }

        $context["filters"] = [
                                [ 'row' => 'Service Date',
                                  'fields' => [
                                    [ 'label' => 'Service Date', 'fieldname' => 'sdate', 'type' => 'daterange', 'data' => ''],
                                   ]
                                ], 
                                [ 'row' => '',
                                  'fields' => [
                                    [ 'label' => 'Service Type', 'fieldname' => 'service_type', 'type' => 'checkbox', 'data' => $service_types], 
                                    [ 'label' => 'Client Type', 'fieldname' => 'client_type', 'type' => 'checkbox', 'data' => $client_types]
                                   ]
                                ], 
                                [ 'row' => '',
                                  'fields' => [
                                    [ 'label' => 'Sex', 'fieldname' => 'sex', 'type' => 'checkbox', 'data' => $sex],
                                    [ 'label' => 'Activity Venue', 'fieldname' => "venue", 'type' => 'combo', 'data' => $venueData]
                                   ]
                                ]
        ];
        
        $context["search"] = [];

        $context["columns"] = [
            [ "id" => "shc_id", "label" => "SHC ID" ],
            [ "id" => "sdate", "label" => "Service Date" ],
            [ "id" => "service_type", "label" => "Service Type" ],
            [ "id" => "client_type", "label" => "Client Type" ],
            [ "id" => "venue", "label" => "Activity Venue" ],
            [ "id" => "sex", "label" => "Sex" ],
            [ "id" => "count", "label" => "Count" ]
        ];

        return view('services.view', $context);
    }
}