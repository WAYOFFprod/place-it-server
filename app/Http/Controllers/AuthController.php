<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public  function getSession(){
        $user = Auth::user();
        if(empty($user)) {
            return response()->json([
                'isConnected' => false
            ], 200);
        }
        return response()->json([
            'isConnected' => true,
            'email' => $user->email
        ], 200);
    }


}
