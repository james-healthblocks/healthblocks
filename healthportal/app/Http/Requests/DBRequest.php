<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class DBRequest extends Request
{
    public function dbParams(){
        $request = array_replace_recursive($this->input(), $this->allFiles());
        // $pageNo = $request["start"];
        // $pageSize = $request["length"];
        // $columnToSort = $request["columns"][$request["order"][0]["column"]]["data"];
        // $sortDirection = $request["order"][0]["dir"];

        // $params = [
        //     "pageNo" => $pageNo,
        //     "pageSize" => $pageSize,
        //     "columnToSort" => $columnToSort,
        //     "sortDirection" => $sortDirection
        // ];

        return $request;

    }
}
