<?php

use Illuminate\Database\Seeder;

class STITestsTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ "field_name" => "sti_name", "value" => "syp_scr_res", "text" => "Syphilis (Reactive)" ],
            [ "field_name" => "sti_name", "value" => "syp_conf_res", "text" => "Syphilis (Positive)" ],
            [ "field_name" => "sti_name", "value" => "gono_res", "text" => "Gonorrhea" ],
            [ "field_name" => "sti_name", "value" => "ngi_res", "text" => "NGI" ],
            [ "field_name" => "sti_name", "value" => "tri_res", "text" => "Trichomoniasis" ],
            [ "field_name" => "sti_name", "value" => "hepab_res", "text" => "Hepatitis B" ],
            [ "field_name" => "sti_name", "value" => "hepac_res", "text" => "Hepatitis C" ],
            [ "field_name" => "sti_name", "value" => "gen_warts_res", "text" => "Genital Warts" ],
            // [ "field_name" => "sti_name", "value" => "gen_anal_res", "text" => "Genito-Anal Warts" ],
            [ "field_name" => "sti_name", "value" => "anal_warts_res", "text" => "Anal Warts" ],
            [ "field_name" => "sti_name", "value" => "herpes_res", "text" => "Herpes" ],
            [ "field_name" => "sti_name", "value" => "bacvag_res", "text" => "Bacterial Vaginosis" ],
            // [ "field_name" => "sti_name", "value" => "cand_res", "text" => "Candidiasis" ],

            [ "field_name" => "sti_test", "value" => "syp_scr_res", "text" => "syp_scr" ],
            [ "field_name" => "sti_test", "value" => "syp_conf_res", "text" => "syp_conf" ],
            [ "field_name" => "sti_test", "value" => "gono_res", "text" => "gram_stain" ],
            [ "field_name" => "sti_test", "value" => "ngi_res", "text" => "gram_stain" ],
            [ "field_name" => "sti_test", "value" => "tri_res", "text" => "wet_mount" ],
            [ "field_name" => "sti_test", "value" => "hepab_res", "text" => "hbsag" ],
            [ "field_name" => "sti_test", "value" => "hepac_res", "text" => "hepac" ],
            [ "field_name" => "sti_test", "value" => "gen_warts_res", "text" => "gen_warts_insp" ],
            // [ "field_name" => "sti_test", "value" => "gen_anal_res", "text" => "gen_anal_insp" ],
            [ "field_name" => "sti_test", "value" => "anal_warts_res", "text" => "anal_warts_insp" ],
            [ "field_name" => "sti_test", "value" => "herpes_res", "text" => "herpes_insp" ],
            [ "field_name" => "sti_test", "value" => "bacvag_res", "text" => "bacvag_insp" ],
            // [ "field_name" => "sti_test", "value" => "cand_res", "text" => "cand_insp" ]
    	];

        DB::table('text')->insert($data);
    }
}
