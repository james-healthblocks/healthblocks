<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Inventory;
use App\Text;
use App\User;
use DB;
use DateTime;
use Carbon\Carbon;

use Excel;

class InventoryController extends Controller
{
    public function isFirstQuarter($year = null, $prev = false){
        $now = Carbon::now();

        if($now->quarter == 1){
            if(!is_null($year)){
                if($year == $now->year){
                    return true;
                }else if($year == ($now->year)-1 && $prev){
                    return true;
                }
            }else{
                return true;
            }
        }

        return false;
    }

    public function updateSucceedingEntries($query, $month, $year){
        $sucEntries = clone $query;
        $sucEntries = $sucEntries->newQuery()
                         ->where(function($q) use ($month, $year){
                                $q->where([
                                        ['month', '>', $month],
                                        ['year', $year]
                                    ]);

                                if($this->isFirstQuarter($year, true)){
                                    $q->orWhere(function ($q) use ($month, $year){
                                        $q->whereIn('month', [1, 2, 3])
                                          ->where('year', $year+1);
                                    });
                                }
                         })->get();

        if($sucEntries){
            $sucEntries = $sucEntries->toArray();
            foreach($sucEntries as $row){
                $qq = clone $query;
                $qq = $qq->where([
                        ['month', $row['month']],
                        ['year', $row['year']]
                    ]);

                $id = $qq->newQuery()->value('id');

                $prevCount = clone $query;
                $prevCount = $prevCount->select('start_amt', 'procuredcount', 'distributedcount')
                                        ->where('month', ($row['month']==1 ? 12 : $row['month']-1))
                                        ->where('year', ($row['month']==1 ? $row['year']-1 : $row['year']))
                                        ->first();

                if($prevCount){
                    $start_amt = $prevCount['start_amt'] + $prevCount['procuredcount'] - $prevCount['distributedcount'];
                }else{
                    $start_amt = 0;
                }

                $update = Inventory::find($id);
                $update->start_amt = $start_amt;
                $update->save();
            }
        }else{
            return false;
        }
    }

    public function syncRows($shc_id = 0){ //repopulate DB based on transactions made
        function getCategory($item_name, $ref, $cat){
            foreach($ref as $category => $names){
                return $cat[array_search($item_name, $names)];
            }
        }

        $thisYear = Carbon::now()->year;
        $thisMonth = Carbon::now()->month;
        $dateNow = Carbon::createFromDate($thisYear, $thisMonth, null);

        $categories = Text::where('field_name', 'inventory_category')->pluck('text')->toArray();
        $catRef = [];
        $items = [];

        foreach($categories as $category){
            $item_names = Text::where('field_name', $category)->pluck('text')->toArray();
            $catRef[$category] = $item_names;
            $buf = [];
            foreach($item_names as $i => $item){
                $qq = Inventory::where('shc_id', $shc_id)
                                ->where('item_name', $item)
                                ->where(function($q) use ($thisYear){
                                    $q->where('year', $thisYear);
                                    if($this->isFirstQuarter()){
                                        $q->orWhere('year', $thisYear-1);
                                    }
                                });

                $expiry_dates = clone $qq;
                $expiry_dates = $expiry_dates->select('expiry_date')->distinct()->pluck('expiry_date')->toArray();

                foreach($expiry_dates as $exp){
                    $minYear = clone $qq;
                    $minMonth = clone $qq;
                    $minYear = $minYear->where('expiry_date', $exp)->min('year');
                    $minMonth = $minMonth->where('expiry_date', $exp)->where('year', $minYear)->min('month');

                    $buf[$item][$exp] = [
                        "month" => $minMonth,
                        "year" => $minYear
                    ];
                }
            }

            $items = array_merge($items, $buf);
        }

        foreach($items as $item_name => $item){
            foreach($item as $expiry_date => $start){
                $date = Carbon::createFromDate($start["year"], $start["month"], null);

                $i = 0;
                while($date <= $dateNow){
                    $thisRow = Inventory::where('shc_id', $shc_id)
                                        ->where('item_name', $item_name)
                                        ->where('expiry_date', $expiry_date)
                                        ->where('month', $date->month)
                                        ->where('year', $date->year)
                                        ->value('id');

                    $prevMonth = clone $date;
                    $prevMonth = $prevMonth->subMonth();

                    $prevCount = Inventory::where('shc_id', $shc_id)
                                        ->where('item_name', $item_name)
                                        ->where('expiry_date', $expiry_date)
                                        ->where('month', $prevMonth->month)
                                        ->where('year', $prevMonth->year)
                                        ->select('start_amt', 'procuredcount', 'distributedcount')
                                        ->first();

                    if(!is_null($prevCount)){
                        $prevCount = $prevCount->toArray();
                        $start_amt = $prevCount['start_amt'] + $prevCount['procuredcount'] - $prevCount['distributedcount'];
                    }else{
                        $start_amt = 0;
                    }

                    if(!is_null($thisRow)){
                        $thisRow = Inventory::find($thisRow);
                        if($thisRow->start_amt != $start_amt){
                            $thisRow->start_amt = $start_amt;
                            $thisRow->save();
                        }
                    }else{
                        if($start_amt > 0){
                            $rowData = [
                                "shc_id" => $shc_id,
                                "item_name" => $item_name,
                                "expiry_date" => $expiry_date,
                                "month" => $date->month,
                                "year" => $date->year,
                                "category" => getCategory($item_name, $catRef, $categories),
                                "start_amt" => $start_amt
                            ];

                            $newRow = new Inventory;
                            $newRow->fill($rowData);
                            $newRow->save();
                        }
                    }

                    $date = $date->addMonth();
                }

            }
        }
    }

    public function encode(){
    	$context['month'] = date('n');
        $thisYear = new Carbon;
        $thisYear = $thisYear->now()->year;

        $startYear = Inventory::min('year');

        if(is_null($startYear)){
            $context['startYear'] = $thisYear;
        }

        $context['startYear'] = $startYear;
    	$context['thisYear'] = $thisYear; //Generate list of years up to this date

        $categories = new Text;
        $context['categories'] = $categories->where('field_name', 'inventory_category')->pluck('text')->toArray();

        return view('inventory.encode', $context);
    }

    public function retrieve(Request $request, $year, $month, $category){
        $shc_id = 0;

        $inventory = [];
        $categories = new Text;

        $categories = $categories->where('field_name', 'inventory_category')->pluck('text')->toArray();

        foreach($categories as $cat){
            $types = new Text;
            $types = $types->where('field_name', $cat)->pluck('text')->toArray();
            $inventory[$cat] = $types;
        }

        $context["types"] = $inventory[$category];

        $data = new Inventory;
        $previousData = new Inventory;
        $previousData = $previousData->select('item_name', 'expiry_date', 'category', 'batchno', 'remarks')
                                     ->where('category', $category)
                                     ->where(function($query) use ($month, $year){
                                        if($this->isFirstQuarter($year)){ //If still first quarter, include last year's data to cascade
                                            $query->where([
                                                    ['month', '<', $month],
                                                    ['year', $year]
                                                ])
                                                ->orWhere(['year', $year-1]);
                                        }else{
                                            $query->where([
                                                ['month', '<', $month],
                                                ['year', $year]
                                            ]);
                                        }
                                     })
                                     ->groupBy('item_name', 'expiry_date', 'category', 'batchno', 'remarks')
                                     ->selectRaw('sum(procuredcount) as procured, sum(distributedcount) as distributed')
                                     ->get()->toArray();

        foreach($previousData as $i => $item){
            $currentCount = $item["procured"] - $item["distributed"];

            if($currentCount == 0){
                unset($previousData[$i]);
            }else{
                $data = new Inventory;

                $data = $data->where([
                                ['month', $month],
                                ['year', $year],
                                ['item_name', $item['item_name']],
                                ['expiry_date', $item['expiry_date']]
                              ]);

                $id = $data->newQuery()->value('id');

                if(!is_null($id)){
                    $data = Inventory::find($id);
                    $data->batchno = $item['batchno'];
                    $data->remarks = $item['remarks'];
                    $data->start_amt = $currentCount;

                    $data->save();
                }else{
                    $item["shc_id"] = $shc_id;
                    $item["month"] = $month;
                    $item["year"] = $year;
                    $item["start_amt"] = $currentCount;

                    $newEntry = new Inventory;
                    $newEntry->fill($item);
                    $newEntry->save();
                }
            }
        }

        $data = new Inventory;
        $context["data"] = $data->where([
                                ['month', $month],
                                ['year', $year],
                                ['category', $category]
                              ])->orderBy('item_name')->orderBy('batchno', 'ASC')->get();
                                
        foreach($context["data"] as $i => $value){
            $context["data"][$i]["on_hand"] = $value["start_amt"] + $value["procuredcount"] - $value["distributedcount"];
            $context["data"][$i]["expiry_date"] = date("m/d/Y", strtotime($value["expiry_date"]));
        }

        return response()->json($context);
    }

    public function save(Request $request){
        $shc_id = 0;
        $data = json_decode($request->getContent(), true);
        $updateRows = $data["toUpdate"];
        $newRows = $data["newEntries"];

        foreach($updateRows as $row){ //Update rows - updates procured/distributed count only
            $row["expiry_date"] = date("Y-m-d", strtotime($row["expiry_date"]));
            $row["shc_id"] = $shc_id;

            $inventory = new Inventory;
            $inventory->fill($row);

            if($row["procuredcount"] > 0){
                $inventory->add('procuredcount', $row["procuredcount"])->save();
            }

            if($row["distributedcount"] > 0){
                $inventory->add('distributedcount', $row["distributedcount"])->save();
            }

            $newQuery = new Inventory;
            $newQuery = $newQuery->where([ 
                ['shc_id', $shc_id],
                ['item_name', $row["item_name"]],
                ['expiry_date', $row["expiry_date"]]
            ]);

            $this->updateSucceedingEntries($newQuery, $row['month'], $row['year']);
        }

        foreach($newRows as $row){
            $row["expiry_date"] = date("Y-m-d", strtotime($row["expiry_date"]));
            $row["shc_id"] = $shc_id;
            $row["invalid"] = 0;

            $inventory = new Inventory;
            $inventory = $inventory
                ->where([ 
                    ['shc_id', $shc_id],
                    ['item_name', $row["item_name"]],
                    ['month', $row["month"]],
                    ['year', $row["year"]],
                    ['expiry_date', $row["expiry_date"]]
                ]);

            $thisRow = clone $inventory;

            if($thisRow->exists()){ // Duplicate, update existing entry
                $updateData = [
                    "batchno" => $row["batchno"],
                    "remarks" => $row["remarks"]
                ];

                if($row["procuredcount"] > 0){
                    $inventory->add('procuredcount', $row["procuredcount"])->save();
                }

                if($row["distributedcount"] > 0){
                    $inventory->add('distributedcount', $row["distributedcount"])->save();
                }

                $newQuery = new Inventory;
                $newQuery = $newQuery->where([ 
                    ['shc_id', $shc_id],
                    ['item_name', $row["item_name"]],
                    ['expiry_date', $row["expiry_date"]]
                ]);

                $this->updateSucceedingEntries($newQuery, $row['month'], $row['year']);
            }else{ // New record
                
                //Check if item_name in text table, else create entry
                $checkTextTable = Text::where('field_name', $row["category"])
                                        ->where('text', $row["item_name"])
                                        ->exists();

                if(!$checkTextTable){ //Custom text entry
                    $textValue = Text::where('field_name', $row['category'])->max('value') + 1;
                    Text::insert(['field_name' => $row['category'], 'value' => $textValue, 'text' => $row['item_name']]);
                }

                $inventory = new Inventory;
                $inventory->fill($row);
                $inventory->save();
            }
        }

        return response()->json([$data]);
    }

    public function reports(){
        $context = $this->getAddrDropdowns();
        $context['month'] = date('n');
        $thisYear = new Carbon;
        $thisYear = $thisYear->now()->year;

        $startYear = Inventory::min('year');

        if(is_null($startYear)){
            $context['startYear'] = $thisYear;
        }

        $context['startYear'] = $startYear;
        $context['thisYear'] = $thisYear; //Generate list of years up to this date

        return view('inventory.reports', $context);
    }

    public function displayReport(Request $request){
        
        $this->syncRows();
        $context["info"] = $request->all();

        $startMonth = $context["info"]["startMonth"];
        $monthObj = DateTime::createFromFormat('!m', $startMonth);
        $context["info"]["startMonth"] = $monthObj->format('F');

        $endMonth = $context["info"]["endMonth"];
        $monthObj = DateTime::createFromFormat('!m', $endMonth);
        $context["info"]["endMonth"] = $monthObj->format('F');

        $startDate = new DateTime($context["info"]["startYear"] . "-" . $context["info"]["startMonth"]);
        $endDate = new DateTime($context["info"]["endYear"] . "-" . $context["info"]["endMonth"]);

        $context["info"]["months"] = [];
        $context["info"]["monthCount"] = 0;

        $address = $this->getAddrDropdowns();

        $context["info"]["municipality"] = $address["cities"][$context["info"]["municipality"]];
        $context["info"]["province"] = $address["provinces"][$context["info"]["province"]];
        $context["info"]["region"] = $address["regions"][$context["info"]["region"]];

        for ($monthBuf = clone $startDate; $monthBuf <= $endDate ; $monthBuf = $monthBuf->modify("+1 month")) { 
            array_push($context["info"]["months"], $monthBuf->format('M'));
            $context["info"]["monthCount"]++;
        }

        $categories = Text::select('value', 'text')->where('field_name', 'inventory_category')->get();

        $data = [];

        foreach ($categories as $category) {
            $data[$category["text"]] = [];
            $item_types = Text::select('field_name', 'value', 'text')->where('field_name', $category["text"])->get()->toArray();

            foreach($item_types as $item){
                $data[$category["text"]][$item["text"]] = [];
                $remarksContainer = [];

                $total["startAmt"] = "—";
                $total["procured"] = 0;
                $total["distributed"] = 0;
                $total["onHand"] = 0;
                $total["expiring"] = "—";
                $total["avgMonthlyCons"] = "—";
                $total["maxStockReq"] = "—";
                $total["replishmentReqQuant"] = "—";

                for ($date = clone $startDate ; $date <= $endDate ; $date = $date->modify("+1 month")) {
                    $month = $date->format('n');
                    $year = $date->format('Y');

                    $dataThisMonth = Inventory::where('month', $month)->where('year', $year)
                                          ->where('item_name', $item["text"])->get()->toArray();

                    $row = [];
                    $row["startAmt"] = 0;
                    $row["procured"] = 0;
                    $row["distributed"] = 0;
                    $row["onHand"] = 0;
                    $row["expiring"] = 0;

                    foreach ($dataThisMonth as $itemRow) {
                        $row["startAmt"] += $itemRow["start_amt"];
                        $row["procured"] += $itemRow["procuredcount"];
                        $row["distributed"] += $itemRow["distributedcount"];

                        $expiry_date = new DateTime($itemRow["expiry_date"]);
                        $row["onHand"] += $itemRow["start_amt"] + $itemRow["procuredcount"] - $itemRow["distributedcount"];
                        $expiry3Months = clone $date;

                        if($expiry_date <= $expiry3Months->modify("+4 months")->modify("-1 day")){
                            $row["expiring"] += $itemRow["start_amt"] + $itemRow["procuredcount"] - $itemRow["distributedcount"];
                        }

                        if($itemRow["remarks"])
                            array_push($remarksContainer, $itemRow["remarks"]);
                    }


                    $total["procured"] += $row["startAmt"];
                    $total["distributed"] += $row["distributed"];
                    $total["onHand"] += $row["onHand"];

                    $row["avgMonthlyCons"] = Inventory::where('item_name', $item["text"])
                                                        ->where(function ($query) use ($month, $year) {
                                                            $m = $month;
                                                            $y = $year;

                                                            for($i=0; $i < 3; $i++) { 
                                                                $m--;
                                                                if($m <= 0){
                                                                    $m = 12 + $m;
                                                                    $y = $year - 1;
                                                                }

                                                                $query->orWhere(function ($query2) use ($m, $y) {
                                                                    $query2->where('month', $m)
                                                                           ->where('year',  $y);
                                                                });
                                                            }
                                                        })->avg('distributedcount');

                    $row["avgMonthlyCons"] = is_null($row["avgMonthlyCons"]) ? "—" : round($row["avgMonthlyCons"], 2);
                    $row["maxStockReq"] = $row["avgMonthlyCons"] == "—" ? "—" : $row["avgMonthlyCons"] * 6;
                    $row["replishmentReqQuant"] = $row["maxStockReq"] == "—" ? "—" : $row["maxStockReq"] - $row["onHand"];

                    $data[$category["text"]][$item["text"]]["info"][$month] = $row;   
                }

                $data[$category["text"]][$item["text"]]["info"]["total"] = $total;
                $data[$category["text"]][$item["text"]]["remarks"] = implode(", ", $remarksContainer);
            }
        }



        $context["data"] = $data;
        $context["info"]["categoryCounter"] = 0;
        $context["info"]["itemCounter"] = 1;

        return view('inventory.displayreport', $context);
    }

    public function database(){
        $this->syncRows();

        $context["page"] = "inventory-db";

        $months = Inventory::select('month')->distinct()->pluck('month')->toArray();
        $years = Inventory::select('year')->distinct()->pluck('year')->toArray();
        $categories = Text::select('value', 'text')->where('field_name', 'inventory_category')->get();
        $item_types = Text::select('field_name', 'value', 'text');

        foreach($categories as $i => $category){
            $item_types = $item_types->orWhere('field_name', $category['text']);
        }

        $item_types = $item_types->get();

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

        $testCheckbox = [ ["value" => 0, "text" => "filler"] ];

        $context["filters"] = [
                                [ 'row' => '',
                                  'fields' => [
                                    [ 'label' => 'Month', 'fieldname' => 'month', 'type' => 'dropdown', 'data' => $months], 
                                    [ 'label' => 'Year', 'fieldname' => 'year', 'type' => 'dropdown', 'data' => $years]
                                   ]
                                ], 
                                [ 'row' => '',
                                  'fields' => [
                                    [ 'label' => 'Category', 'fieldname' => 'category', 'type' => 'checkbox', 'data' => $categories], 
                                    [ 'label' => 'Item Name', 'fieldname' => 'item_name', 'type' => 'combo', 'data' => $item_types]
                                   ]
                                ]
        ];
         
        $context["search"] = ['item_name'];

        $context["columns"] = [ 
            [ 'id' => "shc_id", 'label' => "SHC ID" ],
            [ 'id' => "month", 'label' => "Month" ],
            [ 'id' => "year", 'label' => "Year" ],
            [ 'id' => "category", 'label' => "Category" ],
            [ 'id' => "item_name", 'label' => "Item Name" ],
            [ 'id' => "expiry_date", 'label' => "Expiry Date" ],
            [ 'id' => "start_amt", 'label' => "Beginning Balance" ],
            [ 'id' => "on_hand", 'label' => "Physical Count/ Current Stock on Hand" ],
            [ 'id' => "procuredcount", 'label' => "Procured this Month" ],
            [ 'id' => "distributedcount", 'label' => "Distributed/ Dispensed/ Losses" ],
            [ 'id' => "remarks", 'label' => "Remarks" ]
         ];

         $context['item_types'] = $item_types;

        return view('inventory.view', $context);
    }

    public function databaseRetrieve(Request $request){
        $request = $request->all();

        $pageNo = $request["start"];
        $pageSize = $request["length"];
        $sortColumn = $request["columns"][$request["order"][0]["column"]]["data"];
        $sortDirection = $request["order"][0]["dir"];

        $dbData = new Inventory;
        $dbDataFillable = $dbData->getFillable();

        $recordsTotal = $dbData->count();

        $dbDataFillable = implode(', ', $dbDataFillable);
        $dbData = $dbData->select(DB::raw($dbDataFillable.', start_amt + procuredcount - distributedcount AS on_hand'))
                ->orderBy($sortColumn, $sortDirection)
                ->skip($pageNo*$pageSize)->take($pageSize)->get();

        $recordsFiltered = sizeof($dbData);

        $data = [
            'draw' => $request["draw"] + 1,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $dbData
        ];

        return response()->json($data);
    }
}