<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Icr;
use App\Shclinic;
use App\Text;
use App\Duplicate;
use App\Api\HealthNetworkClient;

use DB;
use DateTime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class ClientController extends Controller
{
    public function search(Request $request){
        $context = [
            'results'=>null,
            'health_network'=>null,
            'uic'=>null
        ];

        if ($request->isMethod('post')){
            $uic = trim($request->input('uic'));
            if ($uic == ''){
                $context['results'] = [];
                $context['uic'] = $uic;
                $context['isUIC'] = False;
            } else {
                $name = explode(' ', $uic);
                if (count($name) == 1){
                    $name[] = $name[0];
                }
                $context['results'] = Icr::where('uic', $uic)
                                         ->orWhere('firstname', 'LIKE', $name[0] . '%')
                                         ->orWhere('lastname', 'LIKE', $name[1] . '%')
                                         ->orderBy('consult_date', 'desc')
                                         ->get()
                                         ->unique('client_id');

                $hnc = new HealthNetworkClient();
                $context['health_network'] = $hnc->search($uic);
                $context['uic'] = $uic;
                $context['isUIC'] = preg_match('/^[a-zA-Z]{4}[0-9]{10}$/', $uic);
            }
        }
        return view('client.search', $context);
    }

    public function newClient(Request $request, $uic){
        $context = $this->getContext();
        $icr = new Icr;
        $icr->consulttype = 1;  // registration
        if ($request->isMethod('post')){
            $data = Input::all();
            $icr->fill($data);
            if ($icr->validate($data)){
                $icr->image = $icr->uploadFile($request);
                $icr->save();
                return redirect()->route(
                    'editConsult',
                    [
                        'uic' => $icr->uic,
                        'id' => $icr->client_id,
                        'pk' => $icr->id
                    ]
                );
            } else {
                $context['errors'] = $icr->errors;
            }
        }
        $icr->uic = $uic;
        $context['icr'] = $icr;

        return view('client.form', $context);
    }

    public function listConsult(Request $request, $uic, $id){
        $context = $this->getContext();

        $context['results'] = Icr::where('uic', $uic)
                                 ->where('client_id', $id)
                                 ->orderBy('consult_date', 'desc')
                                 ->get();

        if (!$context['results']) abort(404);
        $first = $context['results']->first();
        $context['firstname'] = $first['firstname'];
        $context['lastname'] = $first['lastname'];
        $context['uic'] = $uic;
        $context['id'] = $id;

        return view('client.list', $context);
    }

    public function newConsult(Request $request, $uic, $id){
        $context = $this->getContext();

        $latest_icr = Icr::where('uic', $uic)
                  ->where('client_id', $id)
                  ->orderBy('created_at', 'desc')
                  ->first();
        if (!$latest_icr) abort(404);
        $new_icr = $latest_icr->newConsult();
        if ($request->isMethod('post')){
            $data = Input::all();
            $new_icr->fill($data);
            if ($new_icr->validate($data)){
                $new_icr->image = $icr->uploadFile($request);
                $new_icr->save();
                return redirect()->route(
                    'editConsult',
                    [
                        'uic' => $new_icr->uic,
                        'id' => $new_icr->client_id,
                        'pk' => $new_icr->id
                    ]
                );
            } else {
                $context['errors'] = $new_icr->errors;
            }
        }

        $context['icr'] = $new_icr;
        return view('client.form', $context);

    }

    public function editConsult(Request $request, $uic, $id, $pk){
        $context = $this->getContext();
        $icr = Icr::find($pk);
        if (!$icr){
            abort(404);
        }
        if ($request->isMethod('post')){
            $data = Input::all();
            $icr->fill($data);
            if ($icr->validate($data)){
                $image = $icr->uploadFile($request);
                if ($image){
                    if($icr->image){
                        $icr->deleteImage();
                    }
                    $icr->image = $image;
                }
                $icr->save();
            } else {
                $context['errors'] = $icr->errors;
            }
        }
        $context['icr'] = $icr;
        return view('client.form', $context);
    }

    public function database(){
        $context["page"] = "client-db";

        $months = Icr::select(DB::raw('DISTINCT MONTH(consult_date) as months'))->orderBy('months', 'asc')->pluck('months')->toArray();
        $years = Icr::select(DB::raw('DISTINCT YEAR(consult_date) as years'))->pluck('years')->toArray();

        $risk_groups = Text::select('value', 'text')->where('field_name', 'risk_group')->get()->toArray();
        $sti_diagnosis = Text::select('value', 'text')->where('field_name', 'sti_diagnosis')->get()->toArray();

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

        $sex = Text::select('value', 'text')->where('field_name', 'sex')->get();

        $testCheckbox = [ ["value" => 0, "text" => "filler"] ];
        
        $context["filters"] = [
                                [ 'row' => 'Consult Date',
                                  'fields' => [
                                    [ 'label' => 'Consult Date', 'fieldname' => 'consult_date', 'type' => 'daterange', 'data' => ''],
                                   ]
                                ],  
                                [ 'row' => '',
                                  'fields' => [
                                    [ 'label' => 'Client Groups' , 'fieldname' => 'risk_groups', 'type' => 'checkbox', 'data' => $risk_groups, 'logic_toggle' => true ],
                                    [ 'label' => 'STI Diagnosis' , 'fieldname' => 'sti_diagnosis', 'type' => 'checkbox', 'data' => $sti_diagnosis, 'logic_toggle' => true ]
                                   ]
                                ],
                                [ 'row' => '',
                                  'fields' => [
                                    [ 'label' => 'Sex' , 'fieldname' => 'sex', 'type' => 'checkbox', 'data' => $sex]
                                   ]
                                ]
        ];

        $context["search"] = ['uic', 'firstname', 'middlename', 'lastname'];

        $context["columns"] = [
            [ "id" => "uic", "label" => "UIC" ],
            [ "id" => "consulttype", "label" => "Consult Type" ],
            [ "id" => "firstname", "label" => "First Name" ],
            [ "id" => "middlename", "label" => "Middle Name" ],
            [ "id" => "lastname", "label" => "Last Name" ],
            [ "id" => "consult_date", "label" => "Consult Date" ],
            [ "id" => "birthdate", "label" => "Birthdate" ],
            [ "id" => "age", "label" => "Age" ],
            [ "id" => "sex", "label" => "Sex" ],
            [ "id" => "is_resident", "label" => "Is Resident?" ],
            [ "id" => "municipality", "label" => "City/ Municipality" ],
            [ "id" => "province", "label" => "Province" ],
            [ "id" => "region", "label" => "Region" ],
            [ "id" => "risk_groups", "label" => "Risk Group/s" ],
            [ "id" => "sti_diagnosis", "label" => "STI Diagnosis" ]
        ];

        return view('client.view', $context);
    }

    public function duplicates(){
        $context = $this->getContext();
        return view('client.duplicates', $context);
    }

    public function resolveDuplicates(){
        $context = [];
        $data = Input::all();
        if ($data['duplicate'] == 'true'){
            if (!Icr::latestClientRecords($data['checked'])){
                $latest_client = $data['checked'][0];
            } else {
                $latest_client = $data['checked'][0];
            }
            foreach($data['checked'] as $clientid){
                Duplicate::create([
                    'newest_version' => $latest_client,
                    'client_id' => $clientid, 
                    'duplicate' => true,
                    'reason' => $data['reason']
                ]);
            }
        } else {
            foreach($data['checked'] as $clientid){
                Duplicate::create([
                    'newest_version' => null,
                    'client_id' => $clientid,
                    'duplicate' => false,
                    'reason' => $data['reason']
                ]);
            }
        }
        $context['request'] = print_r($data, true);
        return response()->json($context);
    }

    public function listDuplicates(){
        $context = $this->getContext();
        $context['duplicates'] = Duplicate::getPotentialDuplicates();
        return response()->json($context);
    }

    public function markedDuplicates(){
        $context = $this->getContext();
        $context['duplicates'] = Duplicate::all();
        return view('client.marked_duplicates', $context);
    }

    public function reports() {
        $context = $this->getAddrDropdowns();
        return view('client.reports', $context);
    }

    public function displayReport(Request $request) {
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

        $context["info"]["physician"] = $request->get("physician");

        $section_1 = array(
            array("text" => "Total newly registered clients for this period (1st time registration for the year)"),
            array("text" => "Total number of clients (old and new) for this period"),
            array("text" => "Total number of all registered clients for the year (Cumulative from January to the reporting period)"),
            array("text" => "Total number of clients for this period", "row"=>2),
        );

        $client_groups = array(
            array("text" => "TOTAL", "cols" => "2"), 
            array("text" => "Registered Sex Worker", "cg" => "rg_rsw", "cols" => "2"), 
            array("text" => "Non-registered Establishment-Based Sex Worker", "cg" => "rg_nsw", "cols" => "2"), 
            array("text" => "Freelance Female Sex Worker", "cg" => "rg_fsw", "sex" => 2), 
            array("text" => "MSM", "cg" => "rg_msm", "sex" => 1),
            array("text" => "TGW", "cg" => "rg_tg", "sex" => 1),
            array("text" => "TGM", "cg" => "rg_tg", "sex" => 2),
            array("text" => "PWID", "cg" => "rg_pwid", "cols" => "2"),
            array("text" => "Partner of MSM or PWID", "cg" => "rg_partner", "cols" => "2"),
            array("text" => "OFW/Partners of OFW", "cg" => "rg_ofw", "cols" => "2"),
            array("text" => "Others", "cg" => "rg_others", "cols" => "2"),
            array("text" => "Pregnant", "sex" => 2),
        );

        $age_groups = array(
            array("text" => "0-9 years old", "from"=>0, "to"=>9),
            array("text" => "10-14 years old", "from"=>10, "to"=>14),
            array("text" => "15-17 years", "from"=>15, "to"=>17),
            array("text" => "18-19 years", "from"=>18, "to"=>19),
            array("text" => "20-24 years", "from"=>20, "to"=>24),
            array("text" => "25 and older", "from"=>25)
        );

        $client_type = array(
            array("text" => "Walk-in", "client_type"=>3),
            array("text" => "Mobile", "client_type"=>2),
            array("text" => "Referral from antenatal clinics", "client_type"=>1, "client_ref"=>1),
            array("text" => "Referral from TB DOTS", "client_type"=>1, "client_ref"=>2),
            array("text" => "Referral from other facilities", "client_type"=>1, "client_ref"=>3),
        );

        $section_2 = array(
            array("text" => "Total number of STI tests (RPR, Gram stain, wet mount) "),
            array("text" => "Total number STI screening test results given
(RPR, Gram stain, wet mount)"),
            array("text" => "Total number of STI treatment courses provided
(syphilis, gonorrhea, NGI, trichomonas)"),
            array("text" => "Total number of Hepatitis B vaccination (first dose) given"),
        );

        $sti = [
            "types_1" => array(
                ["text" => "Syphilis", "cols" => 7],
                ["text" => "Hepatitis B", "cols" => 3],
                ["text" => "Hepatitis C", "cols" => 2],
                ["text" => "Gonorrhea & Non-gonococcal Infections", "cols" => 6],
                ["text" => "Warts", "cols" => 4],
            ), 
            "columns_1" => array(
                ["text" => "Number of screening tests done", "dbfld" => "syp_scr"],   // Syphilis
                ["text" => "Number of tests reactive for Syphilis", "dbfld" => "syp_scr_res"],
                ["text" => "Number of screening test results given", "dbfld" => "syp_scr_inf"],
                ["text" => "Number of TPPA/ TPHA done", "dbfld" => "syp_conf"],
                ["text" => "Number of tests positive for TPPA/ TPHA", "dbfld" => "syp_conf_res"],
                ["text" => "Number of TPPA/TPHA results given", "dbfld" => "syp_conf_inf"],
                ["text" => "Number of syphilis treatment courses provided", "dbfld" => "syp_scr_treat"], //syp_conf_treat
                ["text" => "Number of Hepatitis B screening done", "dbfld" => "hbsag"],     // Hepatitis B
                ["text" => "Number of HBsAg positive tests", "dbfld" => "hepab_res"],
                ["text" => "Number of first dose Hepatitis B vaccinations", "dbfld" => "hepab_vac"],
                ["text" => "Number of Hepatitis C screening done", "dbfld" => "hepac"],     // Hepatitis C
                ["text" => "Number of Anti-HCV positive tests", "dbfld" => "hepac_res"],
                ["text" => "Number of gram stains done", "dbfld" => "gram_stain"],     // Gonorrhea
                ["text" => "Number of gram stain results given", "dbfld" => "gram_stain_inf"],
                ["text" => "Number of gram stains w Gm(-) diplococci", "dbfld" => "gono_res"],
                ["text" => "Number of gonorrhea treatment courses provided", "dbfld" => "gono_treat"],
                ["text" => "Number of gram stains w +1 pus cells (male) or +3 pus cells (female)", "dbfld" => "ngi_res"],
                ["text" => "Number of NGI treatment courses provided", "dbfld" => "ngi_treat"],
                ["text" => "Number of genital warts inspection", "dbfld" => "gen_warts_insp"],     // Warts
                ["text" => "Number of genital warts cases", "dbfld" => "gen_warts_res"],
                ["text" => "Number of anal warts inspection", "dbfld" => "anal_warts_insp"],
                ["text" => "Number of anal warts cases", "dbfld" => "anal_warts_res"],
            ),

            "types_2" => array(
                ["text" => "Trichomoniasis", "cols" => 4],
                ["text" => "Bacterial Vaginosis", "cols" => 2],
                ["text" => "Herpes", "cols" => 2],
            ),
            "columns_2" => array(
                ["text" => "Number of wet mounts done", "dbfld" => "wet_mount"],     // Trichomoniasis
                ["text" => "Number of wet mounts positive for trichomonas", "dbfld" => "tri_res"],
                ["text" => "Number of wet mount results given", "dbfld" => "tri_inf"],
                ["text" => "Number of trichomoniasis treatment courses provided", "dbfld" => "tri_treat"],
                ["text" => "Number of Gram Stains done for bacterial vaginosis", "dbfld" => "bacvag_insp"],     // Bacterial Vaginosis
                ["text" => "Number of bacterial vaginosis cases", "dbfld" => "bacvag_res"],
                ["text" => "Number of herpes inspection", "dbfld" => "herpes_insp"],     // Herpes
                ["text" => "Number of herpes cases", "dbfld" => "herpes_res"],
            ),     
        ];  

        function getCGRow($byDescriptionQuery, $client_groups) {
            $rowindex = 0;
            // text, cg, cols, sex
            foreach ($client_groups as $key=>$cg) {
                $byCGroupCount = clone $byDescriptionQuery;

                if (!isset($cg["cg"])) {
                    if (isset($cg["cols"])) {
                        $byCGroupCountF = clone $byCGroupCount;
                        $row[$rowindex++] = $byCGroupCountF->where('sex', 2)->count();
                        $row[$rowindex++] = $byCGroupCount->where('sex', 1)->count();
                    }
                    else {
                        $row[$rowindex++] = $byCGroupCount->where('sex', 2)
                            ->where('is_pregnant', 1)->count();
                    }
                }
                else {
                    $byCGroupCount = $byCGroupCount->where($cg["cg"], 1);

                    if (isset($cg["cols"])) {
                        $byCGroupCountF = clone $byCGroupCount;
                        $row[$rowindex++] = $byCGroupCountF->where('sex', 2)->count();
                        $row[$rowindex++] = $byCGroupCount->where('sex', 1)->count();
                    }
                    else {
                        $row[$rowindex++] = $byCGroupCount->where('sex', $cg["sex"])->count();
                    }
                }
            }

            return $row;
        }

        function getSTIRow($byClientQuery, $sticols) {
            $rowindex = 0;

            foreach ($sticols as $col) {
                $bySTIColCount = clone $byClientQuery;

                
                if ($col["dbfld"] === "syp_scr_treat") {
                    $row[$rowindex++] = $bySTIColCount->whereRaw('(syp_scr_treat = 1 OR syp_conf_treat = 1)')->count();
                }
                else {
                    $row[$rowindex++] = $bySTIColCount->where($col["dbfld"], 1)->count();  
                }
            }

            return $row;
        }

        $data = array();
        $data_s1 = array();
        $data_s2 = array();
        $data_s3 = array();
        $data_s4 = array();

        $icrQuery = new Icr;
        // $icrQuery = $icrQuery->select(DB::raw("YEAR(NOW()) - YEAR(`birthdate`) - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(`birthdate`, '00-%m-%d')) AS age, icr.*"));
        $age_frml = "YEAR(NOW()) - YEAR(`birthdate`) - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(`birthdate`, '00-%m-%d'))";

        $icrQuery_year = clone $icrQuery;
        $icrQuery_year = $icrQuery_year->whereBetween('consult_date', [date("Y") . "01-01", date("Y-m-d")]);
        $icrQuery = $icrQuery->whereBetween('consult_date', [$startDate, $endDate]);
        
        // SECTION 1
        foreach ($section_1 as $index=>$s1) {
            $row = array();
            $byDescriptionQuery = clone $icrQuery;

            switch ($index) {
                case 0:
                    $byDescriptionQuery = $byDescriptionQuery->where("consulttype", 1);
                    break;
                case 1:
                    // wala, kasi total of old and new
                    break;
                case 2:
                    $byDescriptionQuery = clone $icrQuery_year;
                    break;
                default:
                    break;
            }

            if ($index == 3) {
                $data_s1[$index]["text"] = $s1["text"];

                foreach($age_groups as $key => $ag) {
                    $byAgeQuery = clone $byDescriptionQuery;

                    if (isset($ag["to"])) {
                        $byAgeQuery = $byAgeQuery->whereBetween(DB::raw($age_frml), [$ag["from"], $ag["to"]]);
                    }
                    else {
                        $byAgeQuery = $byAgeQuery->whereRaw($age_frml . " > " . $ag["from"]);
                    }
                    
                    $ag_arr["text"] = $ag["text"];
                    $ag_arr["data"] = getCGRow($byAgeQuery, $client_groups);
                    // $data_s1[$index]["age_groups"]["text"] = $ag["text"];
                    $data_s1[$index]["age_groups"][$key] = $ag_arr;
                }

                // client_type. 1: Referral, 2: Mobile, 3: Walk-in
                // client_ref. 1: Antenatal, 2: TBDOTS, 3: Others
                foreach($client_type as $key => $ct) {
                    $byCTypeQuery = clone $byDescriptionQuery;

                    if ($ct["client_type"] != 1) {
                        $byCTypeQuery = $byCTypeQuery->where("client_type", $ct["client_type"]);
                    }
                    else {
                        $byCTypeQuery = $byCTypeQuery->where("client_type", $ct["client_type"])
                            ->where("client_ref", $ct["client_ref"]) ;
                    }

                    $ct_arr["text"] = $ct["text"];
                    $ct_arr["data"] = getCGRow($byCTypeQuery, $client_groups);
                    // $data_s1[$index][$key]["text"] = $ct["text"];
                    $data_s1[$index]["client_types"][$key] = $ct_arr;
                }

            }
            else {
                $data_s1[$index]["text"] = $s1["text"];
                $data_s1[$index]["data"] = getCGRow($byDescriptionQuery, $client_groups);
            }
        }
        // return($data_s1);
        // return;

        // SECTION 2
        foreach ($section_2 as $index=>$s2) {
            $byActivityQuery = clone $icrQuery;

            switch ($index) {
                case 0:
                    // sti tests - RPR, gram stain, wet mount
                    $rows[0] = $byActivityQuery->where("gram_stain", 1);
                    $rows[1] = clone $icrQuery;
                    $rows[1] = $rows[1]->where("wet_mount", 1);
                    break;
                case 1:
                    // sti screening results given - RPR, gram stain, wet mount
                    $rows[0] = $byActivityQuery->where("gram_stain_inf", 1);
                    $rows[1] = clone $icrQuery;
                    $rows[1] = $rows[1]->where("tri_inf", 1);
                    break;
                case 2:    
                    // treatment courses - syphilis, gonorrhea, NGI, trichomonas
                    $rows[0] = $byActivityQuery->whereRaw('(syp_scr_treat = 1 OR syp_conf_treat = 1)');
                    $rows[1] = clone $icrQuery;
                    $rows[1] = $rows[1]->where("gono_treat", 1);
                    $rows[2] = clone $icrQuery;
                    $rows[2] = $rows[2]->where("ngi_treat", 1);
                    $rows[3] = clone $icrQuery;
                    $rows[3] = $rows[3]->where("tri_treat", 1);
                    break;
                case 3:
                    $byActivityQuery = $byActivityQuery->where("hepab_vac", 1);
                    break;
                default:
                    break;
            }

            $data_s2[$index]["text"] = $s2["text"];
            if ($index == 3) {
                
                $data_s2[$index]["data"] = getCGRow($byActivityQuery, $client_groups);
            }
            else {
                $finalCount = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

                foreach ($rows as $query) {
                    $queryResults = getCGRow($query, $client_groups);

                    foreach ($queryResults as $key => $value) {
                        $finalCount[$key] += $value;
                    }
                }

                $data_s2[$index]["data"] = $finalCount;
            }
        }
        // return $data_s2;

        foreach($age_groups as $key => $ag) {
            $byAgeQuery = clone $byDescriptionQuery;

            if (isset($ag["to"])) {
                $byAgeQuery = $byAgeQuery->whereBetween(DB::raw($age_frml), [$ag["from"], $ag["to"]]);
            }
            else {
                $byAgeQuery = $byAgeQuery->whereRaw($age_frml . " > " . 15);
            }
            
            $ag_arr["text"] = $ag["text"];
            $ag_arr["data"] = getCGRow($byAgeQuery, $client_groups);
            // $data_s1[$index]["age_groups"]["text"] = $ag["text"];
            $data_s1[$index]["age_groups"][$key] = $ag_arr;
        }

        // SECTION 3 (1st part)
        $byTotalQuery = clone $icrQuery;
        $data_s3[0]["text"] = "TOTAL";
        $data_s3[0]["data"] = getSTIRow($byTotalQuery, $sti["columns_1"]);

        // Female
        $byFemaleQuery = clone $icrQuery;
        $byFemaleQuery = $byFemaleQuery->where('sex', 2);
        $rowindex = 0;

        $data_s3[1]["text"] = "Female";
        $data_s3[1]["rows"] = 8;

        $byPregnantQuery = clone $byFemaleQuery;
        $byPregnantQuery = $byPregnantQuery->where('is_pregnant', 1);
        $group["text"] = "Pregnant";
        $group["class"] = "pregnant";
        $group["data"] = getSTIRow($byPregnantQuery, $sti["columns_1"]);
        $data_s3[1]["groups"][$rowindex++] = $group;

        $group["text"] = "Total Females";
        $group["class"] = "totalfemale";
        $group["data"] = getSTIRow($byFemaleQuery, $sti["columns_1"]);
        $data_s3[1]["groups"][$rowindex++] = $group;
        
        $group = array();

        foreach($age_groups as $ag) {
            $byAgeGroupQuery = clone $byFemaleQuery;

            if (isset($ag["to"])) {
                $byAgeGroupQuery = $byAgeGroupQuery->whereBetween(DB::raw($age_frml), [$ag["from"], $ag["to"]]);
            }
            else {
                $byAgeGroupQuery = $byAgeGroupQuery->whereRaw($age_frml . " > " . $ag["from"]);
            }

            $group["text"] = $ag["text"];
            $group["data"] = getSTIRow($byAgeGroupQuery, $sti["columns_1"]);
            $data_s3[1]["groups"][$rowindex++] = $group;
        }

        // Male
        $byMaleQuery = clone $icrQuery;
        $byMaleQuery = $byMaleQuery->where('sex', 1);
        $rowindex = 0;

        $data_s3[2]["text"] = "Male";
        $data_s3[2]["rows"] = 7;

        $group["text"] = "Total Males";
        $group["data"] = getSTIRow($byMaleQuery, $sti["columns_1"]);
        $data_s3[2]["groups"][$rowindex++] = $group;
        
        foreach($age_groups as $ag) {
            $byAgeGroupQuery = clone $byMaleQuery;

            if (isset($ag["to"])) {
                $byAgeGroupQuery = $byAgeGroupQuery->whereBetween(DB::raw($age_frml), [$ag["from"], $ag["to"]]);
            }
            else {
                $byAgeGroupQuery = $byAgeGroupQuery->whereRaw($age_frml . " > " . $ag["from"]);
            }

            $group["text"] = $ag["text"];
            $group["data"] = getSTIRow($byAgeGroupQuery, $sti["columns_1"]);
            $data_s3[2]["groups"][$rowindex++] = $group;
        }


        // return $data_s3;

        // SECTION 3 (2nd part)
        // Female
        $byFemaleQuery = clone $icrQuery;
        $byFemaleQuery = $byFemaleQuery->where('sex', 2);
        $rowindex = 0;

        $data_s4["text"] = "Female";
        $data_s4["rows"] = 8;

        $byPregnantQuery = clone $byFemaleQuery;
        $byPregnantQuery = $byPregnantQuery->where('is_pregnant', 1);
        $group["text"] = "Pregnant";
        $group["class"] = "pregnant";
        $group["data"] = getSTIRow($byPregnantQuery, $sti["columns_2"]);
        $data_s4["groups"][$rowindex++] = $group;

        $group["text"] = "Total Females";
        $group["class"] = "totalfemale";
        $group["data"] = getSTIRow($byFemaleQuery, $sti["columns_2"]);
        $data_s4["groups"][$rowindex++] = $group;

        $group = array();
        
        foreach($age_groups as $ag) {
            $byAgeGroupQuery = clone $byFemaleQuery;

            if (isset($ag["to"])) {
                $byAgeGroupQuery = $byAgeGroupQuery->whereBetween(DB::raw($age_frml), [$ag["from"], $ag["to"]]);
            }
            else {
                $byAgeGroupQuery = $byAgeGroupQuery->whereRaw($age_frml . " > " . $ag["from"]);
            }

            $group["text"] = $ag["text"];
            $group["data"] = getSTIRow($byAgeGroupQuery, $sti["columns_2"]);
            $data_s4["groups"][$rowindex++] = $group;
        }

        // return $data_s4;


        $data["s1"] = $data_s1;
        $data["s2"] = $data_s2;
        $data["s3"] = $data_s3;
        $data["s4"] = $data_s4;

        $context["data"] = $data;

        $context["info"]["sections"] = array(
            "CLIENT PROFILE",
            "SEXUALLY TRANSMITTED INFECTIONS",
            "SEXUALLY TRANSMITTED INFECTIONS BY KEY AFFECTED POPULATION",
        );
        $context["info"]["client_groups"] = $client_groups;
        $context["info"]["sti"] = $sti;
        $context["info"]["sectionCounter"] = 0;
        
        return view('client.displayreport', $context);
    }
}