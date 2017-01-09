<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Reached;
use App\Service;
use App\Text;
use DB;
use DateTime;

use Excel;

class ReachedController extends Controller
{
    public function import() {
        return view('reached.import');
    }

    public function reports() {
        $context = $this->getAddrDropdowns();
        return view('reached.reports', $context);
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

        $sections = array(
            array("text" => "Number of MSM reached", "cg" => "MSM"), 
            array("text" => "Number of TG reached", "cg" => "TG"), 
            array("text" => "Number of PWID reached", "cg" => "PWID"), 
            array("text" => "Number of FSW reached", "cg" => "FSW")
        );

        $agegroups = array(
            array("text" => "0-9 years old", "from"=>0, "to"=>9),
            array("text" => "10-11 years old", "from"=>10, "to"=>11),
            array("text" => "12-14 years old", "from"=>12, "to"=>14),
            array("text" => "15-17 years", "from"=>15, "to"=>17),
            array("text" => "18-19 years", "from"=>18, "to"=>19),
            array("text" => "20-24 years", "from"=>20, "to"=>24),
            array("text" => "25 and older", "from"=>0)
        );

        $data = array();

        $reachedQuery = new Reached;
        $reachedQuery = $reachedQuery->whereBetween('date_reached', [$startDate, $endDate]);

        foreach ($sections as $section) {
            foreach ($agegroups as $age) {
                $iduCount = 0;
                $byAgeGroupCount = clone $reachedQuery;

                if (isset($age["to"])) {
                    $byAgeGroupCount = $byAgeGroupCount->whereBetween('age', [$age["from"], $age["to"]]);
                }
                else {
                    $byAgeGroupCount = $byAgeGroupCount->where('age', ">=", $age["from"]);
                }

                if ($section["cg"] == "PWID") {
                    $iduCount = clone $byAgeGroupCount;
                    $iduCount = $iduCount->where("risk_group", "IDU")->count();
                }

                $byAgeGroupCount = $byAgeGroupCount->where("risk_group", $section["cg"])
                    ->count();


                $data[$section["text"]][$age["text"]]["count"] = ($byAgeGroupCount + $iduCount);
            }
        }

        $byYkapCount = clone $reachedQuery;
        $byYkapCount = $byYkapCount->where("risk_group", "YKAP")->count();

        $data["Total YKAP reached"]["count"] = $byYkapCount;

        $context["data"] = $data;
        $context["info"]["sectionCounter"] = 0;
        
        return view('reached.displayreport', $context);
    }

    public function download(Request $request) {
        $info = json_decode($request->get("info"), true);
        $data = json_decode($request->get("data"), true);     

        $context = [
            "info" => $info,
            "data" => $data
        ];
        
        $file = Excel::create('SHCReps Outreach Report', function($excel) use ($context) {
            $excel->sheet('Outreach', function($sheet) use ($context){
                $sheet->loadView('reached.xlsReportView', $context);
                $sheet->setOrientation('portrait');
                // Set width for multiple cells
                $sheet->setWidth(array(
                    'A'     =>  50,
                    'B'     =>  8
                ));
            });
        });

        return $file->export('xls');
    }
}