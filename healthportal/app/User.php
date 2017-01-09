<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

use Auth;
use Hash;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'role',
        'region', 'province', 'municipality'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeCreate($info) {
        
        User::create($info);
    }

    public function scopeUsers($id) {
        if (Auth::user()->role == config("constants.SHC_ADMIN")) {
            return User::select('user.*', 'role.rolename')
                ->join('role', 'role.role_id', '=', 'user.role')
                ->where('user.role', '<', config("constants.CENTRAL_ADMIN"))
                ->where('user.id', '!=', Auth::user()->id)
                ->get();
        }
        else if (Auth::user()->role == config("constants.CENTRAL_ADMIN")) {
            return User::select('user.*', 'role.rolename')
                ->join('role', 'role.role_id', '=', 'user.role')
                ->where('user.role', '>=', config("constants.CENTRAL_ADMIN"))
                ->where('user.id', '!=', Auth::user()->id)
                ->get();
        }
        return " ";
    }

    public function bumpKey(){
        $this->attributes['api_created'] = date('Y-m-d H:i:s');
    }

    public function createApiKey(){
        $this->attributes['api_token'] = Str::random(60);
        $this->bumpKey();
    }
}