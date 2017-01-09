<?php

use Illuminate\Database\Seeder;

class STIDiagnosisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            [ "field_name" => "sti_diagnosis", "value" => "syp_scr_res", "text" => "Reactive for Syphilis" ],
            [ "field_name" => "sti_diagnosis", "value" => "syp_conf_res", "text" => "Positive for Syphilis" ],
            [ "field_name" => "sti_diagnosis", "value" => "gono_res", "text" => "Positive for Gonorrhea" ],
            [ "field_name" => "sti_diagnosis", "value" => "ngi_res", "text" => "Positive for NGI" ],
            [ "field_name" => "sti_diagnosis", "value" => "tri_res", "text" => "Positive for Trichomoniasis" ],
            [ "field_name" => "sti_diagnosis", "value" => "hepab_res", "text" => "Positive for Hepatitis B" ],
            [ "field_name" => "sti_diagnosis", "value" => "hepac_res", "text" => "Positive for Hepatitis C" ],
            [ "field_name" => "sti_diagnosis", "value" => "gen_warts_res", "text" => "With Genital Warts" ],
            // [ "field_name" => "sti_diagnosis", "value" => "gen_anal_res", "text" => "With Genito-Anal Warts" ],
            [ "field_name" => "sti_diagnosis", "value" => "anal_warts_res", "text" => "With Anal Warts" ],
            [ "field_name" => "sti_diagnosis", "value" => "herpes_res", "text" => "With Herpes" ],
            [ "field_name" => "sti_diagnosis", "value" => "bacvag_res", "text" => "With Bacterial Vaginosis" ],
            // [ "field_name" => "sti_diagnosis", "value" => "cand_res", "text" => "With Candidiasis" ]
    	];

        DB::table('text')->insert($data);
    }
}
