<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;

use Excel;
use DateTime;
use PDF;
use URL;
use Carbon\Carbon;

use App\Icr;
use App\Text;

class STIGraphsController extends Controller
{

    public function view(){
    	$context = $this->getAddrDropdowns();
        $context['month'] = date('n');
        $context['thisYear'] = 2016; //Generate list of years up to this date
    	$context['sti'] = Text::select('value', 'text')->where('field_name', 'sti_name')->get()->toArray();

        return view('sti_graphs.generate', $context);
    }

    public function display(Request $request){
    	$requestData = $request->all();

    	$startMonth = $requestData["startMonth"];
        $monthObj = DateTime::createFromFormat('!m', $startMonth);
        $context["info"]["startMonth"] = $monthObj->format('F');

        $endMonth = $requestData["endMonth"];
        $monthObj = DateTime::createFromFormat('!m', $endMonth);
        $context["info"]["endMonth"] = $monthObj->format('F');

    	$startDate = new DateTime($requestData["startYear"] . "-" . $context["info"]["startMonth"]);
        $endDate = new DateTime($requestData["endYear"] . "-" . $context["info"]["endMonth"]);

        $data = [];
        $rawData = [];

        $context["months"] = [];
        $context["sti_list"] = [];
        $context["years"] = [];

        for($month = clone $startDate; $month <= $endDate ; $month=$month->modify("+1 month")) {
            if($requestData["startYear"] != $requestData["endYear"]){
                $monthText = $month->format('F Y');
            }else{
                $monthText = $month->format('F');
            } 
        	array_push($context["months"], $monthText);
        }

        for($y = $requestData["startYear"]; $y <= $requestData["endYear"]; $y++){
            array_push($context["years"], $y);
        }

        foreach($requestData["sti"] as $col => $val){
        	$monthlyData = [];
        	$monthlyData["male"] = [];
        	$monthlyData["pregnant"] = [];
        	$monthlyData["not_pregnant"] = [];

        	$tested_male = [];
        	$tested_female = [];
        	$rawCount_male = [];
        	$rawCount_pregnant = [];
        	$rawCount_not_pregnant = [];

        	$thisTest = Text::where('field_name', 'sti_test')->where('value', $col)->value('text');

        	for ($start = clone $startDate; $start <= $endDate ; $start = $start->modify("+1 month")) {
        		$male_percent = 0;
        		$female_pregnant_percent = 0;
        		$female_not_pregnant_percent = 0;

        		$end = clone $start;
        		$end = $end->modify("+1 month")->modify("-1 day");

        		$query = Icr::whereBetween('consult_date', [$start, $end])
        				 ->where($thisTest, '1');
        		
        		$resCountQuery = clone $query;
        		$resCountQuery = $resCountQuery->where($col, '1');

	        	$female = clone $resCountQuery;
	        	$female = $female->where('sex', '2');

	        	$female_pregnant = clone $female;
	        	$female_pregnant = $female_pregnant->where('is_pregnant', '1')->count();

	        	$female = $female->count();
	        	$female_not_pregnant = $female - $female_pregnant;

	        	$male = $resCountQuery->where('sex', '1')->count();

	        	$testedCount_male = clone $query;
	        	$testedCount_male = $testedCount_male->where('sex', '1')->count();

	        	$testedCount_female = $query->where('sex', '2')->count();

	        	if($testedCount_male > 0)
	        		$male_percent = round(($male / $testedCount_male) * 100, 2);

	        	if($testedCount_female > 0){
	        		$female_pregnant_percent = round(($female_pregnant / $testedCount_female) * 100, 2);
	        		$female_not_pregnant_percent = round(($female_not_pregnant / $testedCount_female) * 100, 2);
	        	}

	        	array_push($monthlyData["male"], $male_percent);
	        	array_push($monthlyData["pregnant"], $female_pregnant_percent);
	        	array_push($monthlyData["not_pregnant"], $female_not_pregnant_percent);

	        	array_push($tested_male, $testedCount_male);
	        	array_push($tested_female, $testedCount_female);

	        	array_push($rawCount_male, $male);
	        	array_push($rawCount_pregnant, $female_pregnant);
	        	array_push($rawCount_not_pregnant, $female_not_pregnant);
        	}

        	$context["sti_list"][$col] = Text::where('field_name', 'sti_name')->where('value', $col)->value('text');

        	$data[$col] = [
        		[	
        			"name" => "Male",
	        		"data" => $monthlyData["male"],
	        		"stack" => "Male"
        		],
        		[	
        			"name" => "Pregnant Female",
	        		"data" => $monthlyData["pregnant"],
	        		"stack" => "Female"
        		],
        		[	
        			"name" => "Non-pregnant Female",
	        		"data" => $monthlyData["not_pregnant"],
	        		"stack" => "Female"
        		],
        	];

        	if($col == 'bacvag_res'){
        		unset($data[$col][0]);
        		$data[$col] = array_values($data[$col]);
        	}

        	$rawData[$col] = [
        		"tested_male" => $tested_male,
        		"tested_female" => $tested_female,
        		"raw_male" => $rawCount_male,
        		"raw_pregnant" => $rawCount_pregnant,
        		"raw_not_pregnant" => $rawCount_not_pregnant,
                "rate_male" => $monthlyData["male"],
                "rate_pregnant" => $monthlyData["pregnant"],
                "rate_not_pregnant" => $monthlyData["not_pregnant"]
        	];
        }

        $context["data"] = $data;
        $context["rawData"] = $rawData;

        return view('sti_graphs.display', $context);
    }

    public function download(Request $request){
        function getLetter($num) {
            $num = $num - 1;
            $numeric = $num % 26;
            $letter = chr(65 + $numeric);
            $num2 = intval($num / 26);
            if ($num2 > 0) {
                return getNameFromNumber($num2 - 1) . $letter;
            } else {
                return $letter;
            }
        }

        $info = json_decode($request->get("info"), true);
        $data = json_decode($request->get("data"), true);
        $months = json_decode($request->get("months"), true);
        $rawData = json_decode($request->get("rawData"), true);
        $sti_list = json_decode($request->get("sti_list"), true);

        $test = ["A", "A ", "ADSDS", "ASDADA"];

        $file = Excel::create('New file', function($excel) use ($info, $data, $months, $rawData, $sti_list, $test){
            $excel->sheet('New sheet', function($sheet) use ($info, $data, $months, $rawData, $sti_list, $test){
                $row = 1;
                $col = 1;

                $sheet->fromArray($months, '', 'B1');
                $row++;
                $col++;


                $x = 2;
                foreach($data as $sti => $sti_data){
                    $sheet->setCellValue('A'. $x, $sti_list[$sti]);

                    foreach($sti_data as $rowData){
                        $x++;
                        $sheet->setCellValue('A' . $x, $rowData["name"]);

                        $y = 2;
                        foreach($rowData["data"] as $thisData){
                            $sheet->setCellValue(getLetter($y) . $x, $thisData);
                            $y++;
                        }
                    }

                    $x++;
                }
            });
        });

        return $file->export('xls');
    }

    public function downloadPDF(Request $request){
        $context = $request->all();
        unset($context["_token"]);

        $filename = $context['sti'] . ' Positivity Rate for ' . $context['range'];

        $context["months"] = json_decode($context["months"]);
        $context["data"] = json_decode($context["data"]);
        $context["dohLogo"] = public_path() .'/img/doh_logo.png'; 

        $time = Carbon::now();
        $context["timestamp"] = $time->toDateTimeString();

        // return view('sti_graphs.pdfTemplate', $context);
        $pdf = PDF::loadView('sti_graphs.pdfTemplate', $context);
        $pdf = $pdf->setPaper('a4')->setOrientation('landscape');
        return $pdf->download($filename.'.pdf');
    }

}