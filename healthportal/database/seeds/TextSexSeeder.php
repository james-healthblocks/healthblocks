<?php

use Illuminate\Database\Seeder;

class TextSexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ "field_name" => "sex", "value" => 1, "text" => "Male" ],
            [ "field_name" => "sex", "value" => 2, "text" => "Female" ]
    	];

        DB::table('text')->insert($data);
    }
}
