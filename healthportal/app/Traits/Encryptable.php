<?php

namespace App\Traits;


trait Encryptable
{
    public function getAttribute($key){
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable)){
            $value = decrypt($value);
        }
        return $value;
    }

    public function setAttribute($key, $value){
        if (in_array($key, $this->encryptable)){
            $value = encrypt($value);
        }

        parent::setAttribute($key, $value);
    }
}