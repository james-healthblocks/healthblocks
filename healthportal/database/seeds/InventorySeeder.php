<?php

use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            [ "field_name" => "inventory_category", "value" => 1, "text" => "Commodities" ],
            [ "field_name" => "inventory_category", "value" => 2, "text" => "Test Kits and Reagents" ],
            [ "field_name" => "inventory_category", "value" => 3, "text" => "STI Drugs" ],
            [ "field_name" => "inventory_category", "value" => 4, "text" => "Antiretroviral Drugs (ARV)" ],
            [ "field_name" => "inventory_category", "value" => 5, "text" => "Other Drugs" ],

            [ "field_name" => "Commodities", "value" => 1, "text" => "Condoms" ],
            [ "field_name" => "Commodities", "value" => 2, "text" => "Lubricants" ],

            [ "field_name" => "Test Kits and Reagents", "value" => 1, "text" => "HIV 1/2 RDT" ],
            [ "field_name" => "Test Kits and Reagents", "value" => 2, "text" => "Gram Stain Kits" ],
            [ "field_name" => "Test Kits and Reagents", "value" => 3, "text" => "CD4 Reagents" ],
            [ "field_name" => "Test Kits and Reagents", "value" => 4, "text" => "Syphilis RDT" ],
            [ "field_name" => "Test Kits and Reagents", "value" => 5, "text" => "Hepatitis B Test Kits" ],
            [ "field_name" => "Test Kits and Reagents", "value" => 6, "text" => "Hepatitis C Test Kits" ],

            [ "field_name" => "STI Drugs", "value" => 1, "text" => "Azithromycin 500 mg" ],
            [ "field_name" => "STI Drugs", "value" => 2, "text" => "Metronidazole 500 mg" ],
            [ "field_name" => "STI Drugs", "value" => 3, "text" => "Benzathine Penicillin 1.2m" ],
            [ "field_name" => "STI Drugs", "value" => 4, "text" => "Cefixime 400mg" ],

            [ "field_name" => "Antiretroviral Drugs (ARV)", "value" => 1, "text" => "Lamivudine 150mg + Zidovudine 300mg, 60s" ],
            [ "field_name" => "Antiretroviral Drugs (ARV)", "value" => 2, "text" => "Lamivudine 300mg + Tenofovir 300mg + Efavirenz 600mg, 30s" ],
            [ "field_name" => "Antiretroviral Drugs (ARV)", "value" => 3, "text" => "Efavirenz 600mg, 30s" ],
            [ "field_name" => "Antiretroviral Drugs (ARV)", "value" => 4, "text" => "Nevirapine 200mg, 60s" ],
            [ "field_name" => "Antiretroviral Drugs (ARV)", "value" => 5, "text" => "Lamivudine 150mg + Zidovudine 300mg, 60s" ],
            [ "field_name" => "Antiretroviral Drugs (ARV)", "value" => 6, "text" => "Lopinavir 200mg + Ritonavir 50mg, 120s" ]
    	];

        DB::table('text')->insert($data);
    }
}
