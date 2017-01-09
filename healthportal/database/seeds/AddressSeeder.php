<?php

use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run()
    {
        $types = [
            'city' => 'database/data/cities.json',
            'province' => 'database/data/provinces.json',
            'region' => 'database/data/regions.json'
        ];

        foreach($types as $key => $value){
            $json = File::get($value);
            $data = json_decode($json);
            foreach($data as $d){
                DB::table('text')->insert([
                    'field_name' => $key,
                    'value' => $d->code,
                    'text' => $d->{$key}
                ]);
            }
        }

        $json = File::get('database/data/connections.json');
        $data = json_decode($json);
        foreach($data as $d){
            DB::table('address_connections')->insert([
                'city' => $d->city,
                'region' => $d->region,
                'province' => $d->province
            ]);
        }
    }
}