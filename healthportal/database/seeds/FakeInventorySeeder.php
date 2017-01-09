<?php

use Illuminate\Database\Seeder;

use App\Inventory;
use App\Text;

class FakeInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$categories = new Text;
    	$categories = $categories->where('field_name', 'inventory_category')->pluck('text')->toArray();

    	foreach($categories as $cat){
            $types = new Text;
            $types = $types->where('field_name', $cat)->pluck('text')->toArray();
            $inventory[$cat] = $types;
        }

        $shc_id = 0;
        $year = 2016;

        $data = [];

        $thisMonth = 11;

        for ($i=1; $i <= $thisMonth; $i++) { //'Til November
        	foreach ($categories as $category) {
        		if($category == 'Other Drugs'){
        			break;
        		}

        		for ($x = rand(1,3); $x > 0 ; $x--) {
        			$index = rand(0, count($inventory[$category])-1);
		        	$item_name = $inventory[$category][$index];
		        	$expiry_date = rand(2016, 2018) . '-' . rand($i+1, 12) . '-' . rand(1, 28);
		        	$procuredcount = rand(10, 1000);
		        	$distributedcount = rand(10, $procuredcount);

		        	$row = [
		        		'shc_id' => 0,
		        		'month' => $i,
		        		'year' => 2016,
		        		'category' => $category,
		        		'item_name' => $item_name,
		        		'expiry_date' => $expiry_date,
		        		'procuredcount' => $procuredcount,
		        		'distributedcount' => $distributedcount
		        	];

		        	$checkIfExists = Inventory::where('month', $i)->where('shc_id', 0)
		        							  ->where('year', 2016)->where('item_name', $item_name)
		        							  ->where('expiry_date', $expiry_date)->exists();

		        	if(!$checkIfExists){
		        		$newEntry = new Inventory;
		                $newEntry->fill($row);
		                $newEntry->save();
		        	}
		        	
	        	}
        	}

        	
        }

        for ($month=2; $month <= $thisMonth; $month++) {
        	$previousData = new Inventory;
	        $previousData = $previousData->select('item_name', 'expiry_date', 'category', 'batchno', 'remarks')
	                                     ->where([
	                                        ['month', '<', $month],
	                                        ['year', 2016]])
	                                     ->groupBy('item_name', 'expiry_date', 'category', 'batchno', 'remarks')
	                                     ->selectRaw('sum(procuredcount) as procured, sum(distributedcount) as distributed')
	                                     ->get();

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

	                $recordedThisMonth = clone $data;
	                $recordedThisMonth = $recordedThisMonth->exists();

	                if($recordedThisMonth){
	                    $data->update(['batchno' => $item['batchno']]);
	                    $data->update(['remarks' => $item['remarks']]);
	                    $data->update(['start_amt' => $currentCount]);
	                }else{
	                    $item["shc_id"] = $shc_id;
	                    $item["month"] = $month;
	                    $item["year"] = $year;
	                    $item["start_amt"] = $currentCount;

	                    $pCount = rand(0, 500);
	                    $item["procuredcount"] = $pCount;
	                    $item["distributedcount"] = rand(0, $currentCount+$pCount);

	                    unset($item["procured"]);
	                    unset($item["distributed"]);

	                    $item = $item["attributes"];

	                    $newEntry = new Inventory;
	                    $newEntry->fill($item);
	                    $newEntry->save();
	                }
	            }
	        }
        }

    }
}
