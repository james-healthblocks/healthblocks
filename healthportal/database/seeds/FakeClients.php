<?php

use Illuminate\Database\Seeder;

use App\Icr;
use App\Text;

class FakeClients extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

        $firstNames = ['Abigail', 'Alexandra', 'Alison', 'Amanda', 'Amelia', 'Amy', 'Andrea', 'Angela', 'Anna', 'Anne', 'Audrey', 'Ava', 'Bella', 'Bernadette', 'Carol', 'Caroline', 'Carolyn', 'Chloe', 'Claire', 'Deirdre', 'Diana', 'Diane', 'Donna', 'Dorothy', 'Elizabeth', 'Ella', 'Emily', 'Emma', 'Faith', 'Felicity', 'Fiona', 'Gabrielle', 'Grace', 'Hannah', 'Heather', 'Irene', 'Jan', 'Jane', 'Jasmine', 'Jennifer', 'Jessica', 'Joan', 'Joanne', 'Julia', 'Karen', 'Katherine', 'Kimberly', 'Kylie', 'Lauren', 'Leah', 'Lillian', 'Lily', 'Lisa', 'Madeleine', 'Maria', 'Mary', 'Megan', 'Melanie', 'Michelle', 'Molly', 'Natalie', 'Nicola', 'Olivia', 'Penelope', 'Pippa', 'Rachel', 'Rebecca', 'Rose', 'Ruth', 'Sally', 'Samantha', 'Sarah', 'Sonia', 'Sophie', 'Stephanie', 'Sue', 'Theresa', 'Tracey', 'Una', 'Vanessa', 'Victoria', 'Virginia', 'Wanda', 'Wendy', 'Yvonne', 'Zoe', 'Adam', 'Adrian', 'Alan', 'Alexander', 'Andrew', 'Anthony', 'Austin', 'Benjamin', 'Blake', 'Boris', 'Brandon', 'Brian', 'Cameron', 'Carl', 'Charles', 'Christian', 'Christopher', 'Colin', 'Connor', 'Dan', 'David', 'Dominic', 'Dylan', 'Edward', 'Eric', 'Evan', 'Frank', 'Gavin', 'Gordon', 'Harry', 'Ian', 'Isaac', 'Jack', 'Jacob', 'Jake', 'James', 'Jason', 'Joe', 'John', 'Jonathan', 'Joseph', 'Joshua', 'Julian', 'Justin', 'Keith', 'Kevin', 'Leonard', 'Liam', 'Lucas', 'Luke', 'Matt', 'Max', 'Michael', 'Nathan', 'Neil', 'Nicholas', 'Oliver', 'Owen', 'Paul', 'Peter', 'Phil', 'Piers', 'Richard', 'Robert', 'Ryan', 'Sam', 'Sean', 'Sebastian', 'Simon', 'Stephen', 'Steven', 'Stewart', 'Thomas', 'Tim', 'Trevor', 'Victor', 'Warren', 'William'];

        $lastNames = ['Abraham', 'Allan', 'Alsop', 'Anderson', 'Arnold', 'Avery', 'Bailey', 'Baker', 'Ball', 'Bell', 'Berry', 'Black', 'Blake', 'Bond', 'Bower', 'Brown', 'Buckland', 'Burgess', 'Butler', 'Cameron', 'Campbell', 'Carr', 'Chapman', 'Churchill', 'Clark', 'Clarkson', 'Coleman', 'Cornish', 'Davidson', 'Davies', 'Dickens', 'Dowd', 'Duncan', 'Dyer', 'Edmunds', 'Ellison', 'Ferguson', 'Fisher', 'Forsyth', 'Fraser', 'Gibson', 'Gill', 'Glover', 'Graham', 'Grant', 'Gray', 'Greene', 'Hamilton', 'Hardacre', 'Harris', 'Hart', 'Hemmings', 'Henderson', 'Hill', 'Hodges', 'Howard', 'Hudson', 'Hughes', 'Hunter', 'Ince', 'Jackson', 'James', 'Johnston', 'Jones', 'Kelly', 'Kerr', 'King', 'Knox', 'Lambert', 'Langdon', 'Lawrence', 'Lee', 'Lewis', 'Lyman', 'MacDonald', 'Mackay', 'Mackenzie', 'MacLeod', 'Manning', 'Marshall', 'Martin', 'Mathis', 'May', 'McDonald', 'McLean', 'McGrath', 'Metcalfe', 'Miller', 'Mills', 'Mitchell', 'Morgan', 'Morrison', 'Murray', 'Nash', 'Newman', 'Nolan', 'North', 'Ogden', 'Oliver', 'Paige', 'Parr', 'Parsons', 'Paterson', 'Payne', 'Peake', 'Peters', 'Piper', 'Poole', 'Powell', 'Pullman', 'Quinn', 'Rampling', 'Randall', 'Rees', 'Reid', 'Roberts', 'Robertson', 'Ross', 'Russell', 'Rutherford', 'Sanderson', 'Scott', 'Sharp', 'Short', 'Simpson', 'Skinner', 'Slater', 'Smith', 'Springer', 'Stewart', 'Sutherland', 'Taylor', 'Terry', 'Thomson', 'Tucker', 'Turner', 'Underwood', 'Vance', 'Vaughan', 'Walker', 'Wallace', 'Walsh', 'Watson', 'Welch', 'White', 'Wilkins', 'Wilson', 'Wright', 'Young'];

        $tests = ['syp_scr', 'syp_conf', 'gram_stain', 'wet_mount', 'hbsag', 'hepac', 'gen_warts_insp', 'anal_warts_insp', 'herpes_insp', 'bacvag_insp'];

		$results = [
			'syp_scr' => ['syp_scr_res'],
	        'syp_conf' => ['syp_conf_res'],
	        'gram_stain' => ['gono_res', 'ngi_res'],
	        'wet_mount' => ['tri_res'],
	        'hbsag' => ['hepab_res'],
	        'hepac' => ['hepac_res'],
	        'gen_warts_insp' => ['gen_warts_res'],
	        'anal_warts_insp' => ['anal_warts_res'],
	        'herpes_insp' => ['herpes_res'],
	        'bacvag_insp' => ['bacvag_res'],
        ];

        $firstNamesLength = count($firstNames);
        $lastNamesLength = count($lastNames);

        $risk_groups = Text::select('value')->where('field_name', 'risk_group')->pluck('value')->toArray();
        $sti_diagnosis = Text::select('value')->where('field_name', 'sti_diagnosis')->pluck('value')->toArray();


        for ($i=0; $i < 500; $i++) {
        	$data = []; 
        	$data["uic"] = '';

        	for ($j=0; $j < 4; $j++) { 
        		$data["uic"] .= getLetter(rand(1,26));
        	}

        	$data["uic"] .= sprintf('%02d', rand(1,50));
        	$month = sprintf('%02d', rand(1,12));
        	$day = sprintf('%02d', rand(1,28));
        	$year = sprintf('%02d', rand(1940,2015));
        	$data["uic"] .= $month . $day . $year;

        	if(!Icr::where('uic', $data["uic"])->exists()){
	        	$data["consult_date"] = "2016-" . rand(1,12) . "-" . rand(1,28);
	        	$data["birthdate"] = $year . "-" . $month . "-" . $day;

	        	$data["consulttype"] = "1";

	        	$data["firstname"] = $firstNames[rand(0, $firstNamesLength-1)];
	        	$data["lastname"] = $lastNames[rand(0, $lastNamesLength-1)];

	        	$data["sex"] = rand(1,2);
                $data['gender_identity'] = [$data['sex'], $data['sex']+2, 5][rand(0,2)];
	        	$data["is_resident"] = rand(1,2);
	        	$data["is_perm_resident"] = rand(1,2);

	        	$data["municipality"] = Text::where('field_name', 'city')->inRandomOrder()->value('value');
	        	$data["province"] = Text::where('field_name', 'province')->inRandomOrder()->value('value');
	        	$data["region"] = Text::where('field_name', 'region')->inRandomOrder()->value('value');

	        	$rgs = 0;

	        	for ($q=0; $q < rand(0, 5) ; $q++) {
	        		$rgs = 0; 
	        		$randNo = rand(0, count($risk_groups)-1);
	        		$rg = $risk_groups[$randNo];
	        		if(!(($data["sex"] == 1 && $rg =='rg_pwid') || ($data["sex"] == 2 && $rg =='rg_msm'))){
	        			if(array_key_exists('rg_no_known', $data) || ($q > 0 && $rg =='rg_no_known')){
	        				break;
	        			}else{
	        				$data[$rg] = 1;
	        				$rgs++;
	        			}
	        		}	
	        	}

	        	$stis = 0;

	        	for ($q=0; $q < rand(0, 5) ; $q++){
	        		$stis = 0;
	        		$randNo = rand(0, count($tests)-1);
	        		$thisTest = $tests[$randNo];
	        		$result = $results[$thisTest];

	        		foreach($result as $res){
	        			if(!($data["sex"] == 1 && $thisTest == 'bacvag_insp')){
		        			$data[$thisTest] = 1;
	        				$data[$res] = rand(0, 1);
		        		}
	        		}	        		
	        	}

	        	if($data["sex"] == 2){
	        		$data["is_pregnant"] = rand(1,2);
	        	}

	        	$icr = new Icr;
	        	$icr->normalFill($data);
	        	$icr->save();
        	}

        }

    }
}
