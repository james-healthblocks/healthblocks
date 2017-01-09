<?php

use Illuminate\Database\Seeder;

class HealthPortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = File::get('database/data/hpid.csv');
        foreach (explode("\n", $csv) as $line){
            $d = explode(',', $line);
            DB::table('healthportal')->insert([
                'id' => $d[1],
                'name' => $d[0],
                'ip_addr' => 'http://192.168.1.' . strval((intval($d[1]) % 255) . '/')
            ]);
        }
    }
}
