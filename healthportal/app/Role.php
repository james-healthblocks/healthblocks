<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Role extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role';
    protected $primaryKey = 'role_id';
    public $timestamps = false;

    public function scopeRoles($id) {
        if (Auth::user()->role == config("constants.SHC_ADMIN")) {
            return Role::where('role_id', '<', config("constants.CENTRAL_ADMIN"))->pluck('rolename', 'role_id');
        }
        else if (Auth::user()->role == config("constants.CENTRAL_ADMIN")) {
            return Role::where('role_id', '>=', config("constants.CENTRAL_ADMIN"))->pluck('rolename', 'role_id');
        }
        return " ";
    }
}
