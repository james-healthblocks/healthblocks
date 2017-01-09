<?php

use Illuminate\Database\Seeder;

class ServicesClientServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$data = [
            [ "field_name" => "client_type", "value" => 0, "text" => "Registered Sex Workers" ],
            [ "field_name" => "client_type", "value" => 1, "text" => "Non-registered Establishment-based Sex Workers" ],
            [ "field_name" => "client_type", "value" => 2, "text" => "Freelance Sex Workers" ],
            [ "field_name" => "client_type", "value" => 3, "text" => "Males Who Have Sex with Males" ],
            [ "field_name" => "client_type", "value" => 4, "text" => "Transgender Men" ],
            [ "field_name" => "client_type", "value" => 5, "text" => "Transgender Women" ],
            [ "field_name" => "client_type", "value" => 6, "text" => "Persons who Inject Drugs" ],
            [ "field_name" => "client_type", "value" => 7, "text" => "Female Partners of MSM or PWID" ],
            [ "field_name" => "client_type", "value" => 8, "text" => "Overseas Filipino Workers/Partners of OFW" ],
            [ "field_name" => "client_type", "value" => 9, "text" => "Pregnant Women" ],
            [ "field_name" => "client_type", "value" => 10, "text" => "Others" ],

            //Restricted to Male only
            [ "field_name" => "client_type_restriction", "value" => 'male', "text" => "3" ],
            [ "field_name" => "client_type_restriction", "value" => 'male', "text" => "5" ],

            //Restricted to Female only
            [ "field_name" => "client_type_restriction", "value" => 'female', "text" => "4" ],
            [ "field_name" => "client_type_restriction", "value" => 'female', "text" => "7" ],
            [ "field_name" => "client_type_restriction", "value" => 'female', "text" => "9" ],

            [ "field_name" => "tg", "value" => 'm', "text" => "4" ],
            [ "field_name" => "tg", "value" => 'w', "text" => "5" ],

			["field_name" => "service_type", "value" => 1, "text" => "Lecture/Seminar"],
			["field_name" => "service_type", "value" => 2, "text" => "Group Outreach Activity"],
			["field_name" => "service_type", "value" => 3, "text" => "One-on-one Outreach Activity"],
			["field_name" => "service_type", "value" => 4, "text" => "Mobile STI screening (smearing)"],
			["field_name" => "service_type", "value" => 5, "text" => "Mobile HIV counseling and testing"],
    	];

        DB::table('text')->insert($data);
    }
}
