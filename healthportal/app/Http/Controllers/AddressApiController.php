<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AddressConnection;
use App\Text;

class AddressApiController extends Controller
{
    public function all(Request $request){
        $r = $this->getAddrDropdowns();
        return response()->json([
            'choices' => [
                'cities' => $r['cities'],
                'provinces' => $r['provinces'],
                'regions' => $r['regions']
            ],
            'match' => [
                'city' => null,
                'province' => null,
                'region' => null
            ]

        ]);
    }

    private function getColumn($column, $ids){
        $r = Text::where('field_name', $column)
            ->select('value', 'text')
            ->whereIn('value', $ids)
            ->get();

        $result = array();
        foreach($r as $value){
            $result[$value->value] = $value->text;
        }
        return $result;
    }

    public function region(Request $request, $id){
        $connections = AddressConnection::where('region', $id)->get();

        $addr_ids = ['city' => [], 'province' => []];
        foreach($connections as $connection){
            $addr_ids['city'][] = $connection->city;
            $addr_ids['province'][] = $connection->province;
        }

        return response()->json([
            'choices' => [
                'cities' => $this->getColumn('city', $addr_ids['city']),
                'provinces' => $this->getColumn('province', $addr_ids['province']),
                'regions' => null,
            ],
            'match' => [
                'city' => null,
                'province' => null,
                'region' => $id,
            ]
        ]);
    }

    public function province(Request $request, $id){
        $connections = AddressConnection::where('province', $id)->get();

        $city_ids = [];
        foreach($connections as $connection){
            $city_ids[] = $connection->city;
        }

        return response()->json([
            'choices' => [
                'cities' => $this->getColumn('city', $city_ids),
                'provinces' => null,
                'regions' => null,
            ],
            'match' => [
                'city' => null,
                'province' => $id,
                'region' => $connections->first()->region,
            ]
        ]);
    }

    public function city(Request $request, $id){
        $connection = AddressConnection::where('city', $id)->first();
        return response()->json([
            'choices' => [
                'cities' => null,
                'provinces' => null,
                'regions' => null,
            ],
            'match' => [
                'city' => $connection->city,
                'province' => $connection->province,
                'region' => $connection->region,
            ]
        ]);
    }
}
