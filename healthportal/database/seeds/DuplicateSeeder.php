<?php

use Illuminate\Database\Seeder;
use App\Icr;

class DuplicateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // same UIC, different else
        Icr::create([
            'uic' => 'ABCD0101011970',
            'firstname' => 'Duplicate',
            'lastname' => 'A',
            'consult_date' => date('Y-m-d', time()),
            'sex' => 1
        ]);

        Icr::create([
            'uic' => 'ABCD0101011970',
            'firstname' => 'Duplicate',
            'lastname' => 'B',
            'consult_date' => date('Y-m-d', time()),
            'sex' => 1
        ]);

        Icr::create([
            'uic' => 'ABCD0101011970',
            'firstname' => 'Duplicate',
            'lastname' => 'D',
            'consult_date' => date('Y-m-d', time()),
            'sex' => 1
        ]);

        // Different UIC, same first/last name
        Icr::create([
            'uic' => 'ASDF0101011970',
            'firstname' => 'Duplicate',
            'lastname' => 'C',
            'consult_date' => date('Y-m-d', time()),
            'sex' => 1
        ]);

        Icr::create([
            'uic' => 'QWER0101011970',
            'firstname' => 'Duplicate',
            'lastname' => 'C',
            'consult_date' => date('Y-m-d', time()),
            'sex' => 1
        ]);
    }
}
