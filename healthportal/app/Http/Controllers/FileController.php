<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\User;
use App\Icr;
use App\Inventory;
use App\Service;
use App\Text;

use DateTime;
use Excel;
use Schema;
use ZipArchive;
use Crypt;
use Response;
use Hash;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class FileController extends Controller
{
    // Export filtered data in DataBase View to csv/xls/encrypted
    public function export(Request $request, $mode) {
        //TODO: logic filter

        function rearrange($a){
            foreach ($a as $key => $value) {
                $a[$value["value"]] = $value["text"];
            }

            return $a;
        }

        $request = $request->all();

        $hdnfilters = ($request["hdnfilters"]) ? $request["hdnfilters"] : "{}";
        $filters = json_decode($hdnfilters, true);

        if ($mode == 1 || $mode == 2) {
            $hdnsearch = ($request["hdnsearch"]) ? $request["hdnsearch"] : "{}";
            $search = json_decode($hdnsearch, true);
        }

        $exporttype = $request["exporttype"];
        $logic = [ "risk_groups" => 'AND', "sti_diagnosis" => 'AND' ];
        // $logic = '';
        // $columns = Schema::getColumnListing("user");

        switch ($mode) {
            case 1:
                $title = "SHCReps_ICRData";
                $dbData = new Icr;
                // $logic = $request["logic"];
                break;

            case 2:
                $title = "SHCReps_InventoryData";
                $dbData = new Inventory;
                break;

            case 3:
                $title = "SHCReps_ServiceData";
                $dbData = new Service;
                break;
        }

        if ($exporttype == 'xls') {
            $dbDataFillable = $dbData->getFillable();

            //Setup Data
            if($mode == 2) {
                $dbDataFillable = implode(', ', $dbDataFillable);
                $dbData = $dbData->select(DB::raw($dbDataFillable.', start_amt + procuredcount - distributedcount AS on_hand'));            
            }
            else {
                if($mode == 1) {
                    $dbDataFillable = ['uic', 'consulttype', 'firstname', 'middlename', 'lastname', 'consult_date', 'birthdate', 'sex', 'is_resident', 'municipality', 'province', 'region'];

                    $risk_groups = Text::select('value')->where('field_name', 'risk_group')->pluck('value')->toArray();

                    $sti_diagnosis = Text::select('value')->where('field_name', 'sti_diagnosis')->pluck('value')->toArray();

                    $dbDataFillable = array_merge($dbDataFillable, $risk_groups);
                    $dbDataFillable = array_merge($dbDataFillable, $sti_diagnosis);
                }

                $dbData = $dbData->select($dbDataFillable);
                        
            }
        }
        else {
            $dbData = $dbData->select("*");
        }


        //Apply Filters
        foreach($filters as $fieldName => $filter) {
            // echo "<br />";
            // echo $fieldName . " ";
            // print_r($filter);

            if($filter["value"] != "") {
                $hasFilter = true;
                if($filter["type"] == "dropdown") {
                    $filterValues = explode(',', $filter["value"]);
                } else if($filter["type"] == "checkbox") {
                    $filterValues = json_decode($filter["value"], true);
                } else if($filter["type"] == "daterange") {
                    $filterValues = $filter["value"];
                }

                // print_r($filterValues);
                $type = $filter["type"];
                // echo " " . $type . " ";
                
                $dbData = $dbData->where(function($query) use ($filterValues, $fieldName, $mode, $logic, $type) {
                    if($type == "daterange" && $filterValues != "") {
                        if($mode == 3) {
                            $date_field = 'sdate';
                        } 
                        else if($mode == 1){
                            $date_field = 'consult_date';
                        }

                        $query->whereBetween($date_field, [$filterValues['startdate'], $filterValues['enddate']]);
                    } 
                    else {
                        foreach ($filterValues as $filterValue) {
                            if($mode == 2) {
                                if($fieldName == 'category') {
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

                            if($mode == 1) {
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
        if(($mode == 1 || $mode == 2) && $search["value"] != "") {
            $searchValue = $search["value"];
            $searchColumns = json_decode($search["columns"]);
            $dbData = $dbData->where(function($query) use ($searchValue, $searchColumns) {
                foreach($searchColumns as $searchColumn) {
                    $query->orWhere($searchColumn, 'LIKE', $searchValue . '%');
                }
            });
        }

        $dbData = $dbData->get();

        if ($exporttype == 'xls') {
            if($mode == 1){ //Reformat date, compute age, decode sex, risk groups, diagnosis
                $today = new DateTime('today');
                $sexRef = Text::select('value', 'text')->where('field_name', 'sex')->get()->toArray();
                $rgRef = Text::select('value', 'text')->where('field_name', 'risk_group')->get()->toArray();
                $diagRef = Text::select('value', 'text')->where('field_name', 'sti_diagnosis')->get()->toArray();

                $sexRef = rearrange($sexRef);
                $rgRef = rearrange($rgRef);
                $diagRef = rearrange($diagRef);

                foreach($dbData as $index=>$row) {
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
                    foreach ($risk_groups as $rg) {
                        unset($dbData[$index][$rg]);
                    }
                    foreach ($sti_diagnosis as $sd) {
                        unset($dbData[$index][$sd]);
                    }
                }
            }
            if($mode == 3) {
                $services = Text::select('value', 'text')->where('field_name', 'service_type')->get()->keyBy('value');
                $clients = Text::select('value', 'text')->where('field_name', 'client_type')->get()->keyBy('value');
                $venue = Text::select('value', 'text')->where('field_name', 'service_venue')->get()->keyBy('value')->toArray();
                $sex = Text::select('value', 'text')->where('field_name', 'sex')->get()->keyBy('value');
                
                foreach($dbData as $index=>$row) {
                    $dbData[$index]["client_type"] = $clients[$row["client_type"]]["text"];
                    $dbData[$index]["service_type"] = $services[$row["service_type"]]["text"];                
                    $dbData[$index]["sex"] = $sex[$row["sex"]]["text"];
                    $dbData[$index]["sdate"] = date("m/d/Y", strtotime($row["sdate"]));

                    if($row["loc"] == 0) {
                        $dbData[$index]["loc"] = $row["tx"];
                    } 
                    else {
                        $dbData[$index]["loc"] = $venue[$row["loc"]]["text"];
                    }
                }
            }
        }        

        if ($exporttype == 'encrypted') {
            $encrypted = Crypt::encrypt($dbData);

            return response()->make($encrypted, 200, array(
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment;; filename="' . $title . '.bin"',
            'Content-Transfer-Encoding' => 'binary'));

            return $encrypted;
            return Crypt::decrypt($encrypted);

        }
        // else {
            Excel::create($title, function($excel) use($title, $dbData) {

                // Set the title
                $excel->setTitle($title);

                // Chain the setters
                $excel->setCreator('MaroonStudios')
                ->setCompany('MaroonStudios');

                $excel->setDescription('A demonstration to change the file properties');

                $excel->sheet('From Model', function($sheet) use($dbData) {
                    // Sheet manipulation

                    $sheet->fromModel($dbData, null, 'A1', true);

                    // Add as very first
                    // $sheet->prependRow(array(
                    //     '<shc_id>', '<table_name>', '<total_entries>'
                    // ));

                    $sheet->row(1, function($row) {

                        // call cell manipulation methods
                        $row->setBackground('#2e9f83');
                        $row->setFontWeight('bold');
                        $row->setFontColor('#ffffff');
                        $row->setAlignment('center');

                    });

                    $sheet->freezeFirstRow();
                });

                $excel->sheet('From Array', function($sheet) {
                    // Sheet manipulation
                    $sheet->fromArray(array(
                    array('<shc_id>', '<table_name>', '<total_entries>'),
                    ));                    
                });

            })->export($exporttype);
        // }


        // return \Redirect::route('route-name-here');
    }

    public function importCsv(Request $request, $origin, $ext) {

        switch ($origin) {
            case "client":
                $table = "icr";
                break;

            case "inventory":
                $table = "inventory";
                break;

            case "services":
                $table = "service";
                break;

            case "reached":
                $table = "reached";
                break;

            default:
                break;
        }

        if ($ext === "csv") {

            $table_fields = Schema::getColumnListing($table);

            if ($origin == "reached") {
                $table_fields = ["uic", "age", "risk_group", "date_reached"];
            }

            $file = Input::file('uploadfile');
            $path = $file->getRealPath();

            $data = Excel::load($path, function($reader) {
                $reader->formatDates(true, 'Y-m-d');
            })->get();

            // Excel::filter('chunk')->load($path)->chunk(50, function($results)
            // {
            //     foreach($results as $row) {
            //         echo $row . "<br /><br />";
            //     }
            // });

            // return $data;            

            if(!empty($data) && $data->count()) {
                $decode = json_decode($data[0], true);

                $import_fields = array_keys($decode);

                $fields_diff = array_diff_key($table_fields, $import_fields);
                $fields_diff_v = array_diff_key($import_fields, $table_fields);

                if (count($fields_diff) != 0 || count($fields_diff_v) != 0) {
                    return response()->json(array(
                                'type'      => 2,
                                'message'   => "Invalid csv format for " . $origin
                            )); 
                }

                DB::beginTransaction();

                try {
                    foreach($data as $index=>$row) {
                        $decode = json_decode($row, true);
                
                        if ($origin == "services") {
                            $decode["tx"] = ($decode["tx"]) ? $decode["tx"] : "";
                        }

                        if ($origin == "reached") {
                            $decode["risk_group"] = strtoupper($decode["risk_group"]);
                        }

                        DB::table($table)->insert($decode);

                        // foreach($row as $key=>$value) {
                        //     echo $key . "  " . $value . "  ";
                        // }
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    // echo $e;
                    DB::rollback();
                    return response()->json(array(
                        'type'      => 2,
                        'message'   => "Duplicate entry detected."
                    ));

                }

                return response()->json(array(
                    'type'      => 1,
                    'message'   => $data->count() . " entries successfully imported."
                ));
            }
            else {
                return response()->json(array(
                    'type'      => 2,
                    'message'   => "CSV has no data."
                ));
            }

        }

        return response()->json(array(
                                'type'      => 1,
                                'message'   => $ext
        ));
    }

}