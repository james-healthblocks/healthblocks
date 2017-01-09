<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Inventory;
use App\Icr;
use App\Service;
use App\Text;

use DB;
use DateTime;
use Carbon;

class DatabaseViewController extends Controller
{
    public function retrieve(Request $request, $origin){

        function rearrange($a){
            foreach ($a as $key => $value) {
                $a[$value["value"]] = $value["text"];
            }

            return $a;
        }

        $request = $request->all();

        $shc_id = 0;

        $pageNo = $request["start"];
        $pageSize = $request["length"];

        $sortColumn = $request["columns"][$request["order"][0]["column"]]["data"];
        $sortDirection = $request["order"][0]["dir"];

        $filters = $request["filter"];
        $hasFilter = false;

        $search = $request["search"];
        $search["columns"] = $request["searchColumns"];

        $test = "TEST";
        $logic = '';

        switch ($origin) {
            case 'inventory':
                $dbData = new Inventory;
                break;

            case 'client':
                $dbData = new Icr;
                $logic = $request["logic"];
                break;

            case 'services':
                $dbData = new Service;
                break;
        }

        $dbDataFillable = $dbData->getFillable();
        $recordsTotal = $dbData->count();

        //Setup Data
        if($origin == 'inventory'){
            $dbDataFillable = implode(', ', $dbDataFillable);
            $dbData = $dbData->select(DB::raw($dbDataFillable.', start_amt + procuredcount - distributedcount AS on_hand'));            
        }else{
            if($origin == 'client'){
                $dbDataFillable = ['uic', 'consulttype', 'firstname', 'middlename', 'lastname', 'consult_date', 'birthdate', 'sex', 'is_resident', 'municipality', 'province', 'region'];

                $risk_groups = Text::select('value')->where('field_name', 'risk_group')->pluck('value')->toArray();

                $sti_diagnosis = Text::select('value')->where('field_name', 'sti_diagnosis')->pluck('value')->toArray();

                $dbDataFillable = array_merge($dbDataFillable, $risk_groups);
                $dbDataFillable = array_merge($dbDataFillable, $sti_diagnosis);
            }

            $dbData = $dbData->select($dbDataFillable);
                    
        }

        //Apply Filters
        foreach($filters as $fieldName => $filter){
            if($filter["value"] != ""){
                $hasFilter = true;
                if($filter["type"] == "dropdown" || $filter["type"] == "combo"){
                    $filterValues = explode(',', $filter["value"]);
                }else if($filter["type"] == "checkbox"){
                    $filterValues = json_decode($filter["value"], true);
                }else if($filter["type"] == "daterange"){
                    $filterValues = $filter["value"];
                }

                $type = $filter["type"];
                
                $dbData = $dbData->where(function($query) use ($filterValues, $fieldName, $origin, $logic, $type){
                    if($type == "daterange" && $filterValues != ""){
                        if($origin == 'services'){
                            $date_field = 'sdate';
                        }else if($origin == 'client'){
                            $date_field = 'consult_date';
                        }

                        $query->whereBetween($date_field, [$filterValues['startdate'], $filterValues['enddate']]);
                    }else{
                        foreach ($filterValues as $filterValue) {
                            if($origin == 'inventory'){
                                if($fieldName == 'category'){
                                    $filterValue = Text::where('field_name', 'inventory_category')
                                                        ->where('value', $filterValue)
                                                        ->value('text');
                                }

                                if($fieldName == 'item_name'){
                                    $filterValue = explode('-', $filterValue);
                                    $filterValue = Text::where('field_name', $filterValue[0])
                                                        ->where('value', $filterValue[1])
                                                        ->value('text');
                                }
                            }

                            if($origin == 'client'){
                                if($fieldName == 'risk_groups' || $fieldName == 'sti_diagnosis'){
                                    switch ($logic[$fieldName]) {
                                        case 'OR':
                                            $query->orWhere($filterValue, '1');
                                            break;

                                        case 'AND':
                                            $query->where($filterValue, '1');
                                            break;    
                                    }
                                    continue;
                                }
                            }

                            $query->orWhere($fieldName, $filterValue);
                        }
                    }
                });
            }
        }

        //Search
        if($search["value"] != ""){
            $searchValue = $search["value"];
            $searchColumns = json_decode($search["columns"]);
            $dbData = $dbData->where(function($query) use ($searchValue, $searchColumns){
                foreach($searchColumns as $searchColumn){
                    $query->orWhere($searchColumn, 'LIKE', "%{$searchValue}%");
                }
            });
        }

        if($hasFilter || $search["value"] != ""){
            $recordsFiltered = clone $dbData;
            $recordsFiltered = $recordsFiltered->count();
        }else{
            $recordsFiltered = $recordsTotal;
        }

        //Get Data
        $dbData = $dbData->orderBy($sortColumn, $sortDirection)
                         ->skip($pageNo)->take($pageSize)->get()->toArray(); 

        //Reformat Date, Decode Month
        if($origin == 'inventory'){
            foreach($dbData as $i => $data){
                $dbData[$i]["expiry_date"] = date("m/d/Y", strtotime($data["expiry_date"]));
                $dateObj = DateTime::createFromFormat('!m', $dbData[$i]["month"]);
                $dbData[$i]["month"] = $dateObj->format('F');
            }
        }

        if($origin == 'services'){ //decode text
            $services = Text::select('value', 'text')->where('field_name', 'service_type')->get()->keyBy('value')->toArray();
            $clients = Text::select('value', 'text')->where('field_name', 'client_type')->get()->keyBy('value')->toArray();
            $venue = Text::select('value', 'text')->where('field_name', 'service_venue')->get()->keyBy('value')->toArray();
            $sex = Text::select('value', 'text')->where('field_name', 'sex')->get()->keyBy('value')->toArray();
            
            foreach($dbData as $index=>$row){
                $dbData[$index]["client_type"] = $clients[$row["client_type"]]["text"];
                $dbData[$index]["service_type"] = $services[$row["service_type"]]["text"];                
                $dbData[$index]["sex"] = $sex[$row["sex"]]["text"];
                $dbData[$index]["sdate"] = date("m/d/Y", strtotime($row["sdate"]));
            }
        }

        if($origin == 'client'){ //Reformat date, compute age, decode sex, risk groups, diagnosis
            $today = new DateTime('today');
            $sexRef = Text::select('value', 'text')->where('field_name', 'sex')->get()->toArray();
            $rgRef = Text::select('value', 'text')->where('field_name', 'risk_group')->get()->toArray();
            $diagRef = Text::select('value', 'text')->where('field_name', 'sti_diagnosis')->get()->toArray();

            $sexRef = rearrange($sexRef);
            $rgRef = rearrange($rgRef);
            $diagRef = rearrange($diagRef);

            foreach($dbData as $index=>$row){
                $birthdate = new DateTime($row["birthdate"]);
                $dbData[$index]["age"] = $birthdate->diff($today)->y;

                $container = [];

                foreach ($risk_groups as $group) {
                    if($row[$group] == 1){
                        array_push($container, $rgRef[$group]);
                    }
                }

                $dbData[$index]["risk_groups"] = implode(", ", $container);
                $container = [];

                foreach ($sti_diagnosis as $sti) {
                    if($row[$sti] == 1){
                        array_push($container, $diagRef[$sti]);
                    }
                }

                $dbData[$index]["sti_diagnosis"] = implode(", ", $container);

                $dbData[$index]["is_resident"] = $row["is_resident"] == 1 ? "Yes" : "No";
                $dbData[$index]["consult_date"] = date("m/d/Y", strtotime($row["consult_date"]));
                $dbData[$index]["birthdate"] = date("m/d/Y", strtotime($row["birthdate"]));
                $dbData[$index]["sex"] = $sexRef[$row["sex"]];
            }
        }

        $data = [
            'test' => $test,
            'draw' => $request["draw"] + 1,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $dbData
        ];

        return response()->json($data);
    }
}