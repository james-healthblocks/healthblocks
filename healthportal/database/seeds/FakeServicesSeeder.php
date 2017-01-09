<?php

use Illuminate\Database\Seeder;

use App\Text;
use App\Service;

class FakeServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shc_id = 0;

        $service_type = Text::select('value', 'text')->where('field_name', 'service_type')->get()->toArray();
        $clients = Text::select('value', 'text')->where('field_name', 'client_type')->get();

        //Sex specific client types
        $male_only = Text::where('field_name', 'client_type_restriction')->where('value', 'male')->pluck('text')->toArray();
        $female_only = Text::where('field_name', 'client_type_restriction')->where('value', 'female')->pluck('text')->toArray();

        $sexes = Text::where('field_name', 'sex')->pluck('value')->toArray();

        $fakeVenues = ["School", "Church", "Playground", "Airplane", "Zoo", "Beach"];

        for ($i=1; $i <= 11 ; $i++) { 
        	for ($x = rand(0, 5); $x > 0 ; $x--) { 
        		$sdate = "2016-" . $i . "-" . rand(1,28); 

        		foreach($service_type as $service){
        			foreach($clients as $client){
        				foreach($sexes as $sex){
        					if(!(($sex == 1 && in_array($client["value"], $female_only)) || ($sex ==2 && in_array($client["value"], $male_only)))){

                                $venue = $fakeVenues[rand(0, 5)];

        						$data = [
		        					'shc_id' => $shc_id,
		        					'sdate' => $sdate,
		        					'service_type' => $service["value"],
		        					'client_type' => $client["value"],
                                    'venue' => $venue,
		        					'sex' => $sex,
		        					'count' => rand(1, 500)
	        					];

	        					$checkIfExists = Service::where('sdate', $sdate)->where('service_type', $service["value"])
		        							  ->where('client_type', $client["value"])->where('sex', $sex)->where('venue', $venue)->exists();

					        	if(!$checkIfExists){
					        		$newRow = new Service;
		        					$newRow->fill($data);
		        					$newRow->save();
					        	}
        					}
        				}
        			}
        		}
        	}
        }
    }
}
