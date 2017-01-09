<?php

use Illuminate\Database\Seeder;
use App\Shclinic;

class FakeClinic extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "shc_id" => 1,
                "clinicname" => "Test Clinic",
                "image" => "http://www.clipartlord.com/wp-content/uploads/2013/10/hospital5.png"
            ]
    	];

        DB::table('shclinic')->insert($data);
    }
}
