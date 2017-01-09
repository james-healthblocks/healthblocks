<?php

use Illuminate\Database\Seeder;

class RiskGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ "field_name" => "risk_group", "value" => "rg_rsw", "text" => "Registered Sex Workers" ],
            [ "field_name" => "risk_group", "value" => "rg_nsw", "text" => "Non-registered Establishment-Based Sex Workers" ],
            [ "field_name" => "risk_group", "value" => "rg_fsw", "text" => "Freelance Sex Workers" ],
            [ "field_name" => "risk_group", "value" => "rg_cfsw", "text" => "Clients of Female Sex Worker" ],
            [ "field_name" => "risk_group", "value" => "rg_msm", "text" => "Males who have Sex with Males" ],
            [ "field_name" => "risk_group", "value" => "rg_tg", "text" => "Transgenders" ],
            [ "field_name" => "risk_group", "value" => "rg_pwid", "text" => "Persons who inject drugs" ],
            [ "field_name" => "risk_group", "value" => "rg_partner", "text" => "Partner of MSM or PWID" ],
            [ "field_name" => "risk_group", "value" => "rg_ofw", "text" => "OFWs or Partners of OFW" ],
            [ "field_name" => "risk_group", "value" => "rg_others", "text" => "Others" ],
    	];

        DB::table('text')->insert($data);
    }
}
