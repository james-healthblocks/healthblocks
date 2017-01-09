<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\User;

class ApiController extends Controller
{
    public function login(Request $request){
        $email = Input::get('email', '');
        $password = Input::get('password', '');
        if (!$email or !$password){
            abort(503);
        }

        $user = Auth::once(['email' => $email, 'password' => $password]);

        if ($user){
            $user = Auth::user();
            $user->createApiKey();
            $user->save();
            return response()->json([
                'name' => $user->name,
                'shc_id' => $user->shc_id,
                'region' => $user->region,
                'province' => $user->province,
                'municipality' => $user->municipality,
                'api_token' => $user->api_token,
                'login_error' => False
            ]);
        } else {
            return response()->json([
                'login_error' => True
            ]);
        }
    }
}
