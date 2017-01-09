<?php

namespace App\Helpers;

use App\Shclinic;

class ClinicInfo{

    public static function name(){
        $shc = Shclinic::all()->first();

        if($shc)
        	return $shc->clinicname;
       	else
       		return false;
    }

    public static function image(){
        $shc = Shclinic::all()->first();

        if($shc)
        	return $shc->image;
       	else
       		return false;
    }

}