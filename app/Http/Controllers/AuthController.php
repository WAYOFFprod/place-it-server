<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public  function getSession(Request $request){
        $user = Auth::user();
        if(empty($user)) {
            return response()->json([
                'isConnected' => false,
            ], 200);
        }
        return response()->json([
            'isConnected' => true,
            'user' => new UserResource($user),
        ], 200);
    }

    public function update(UpdateUserRequest $request) {
        $user = Auth::user();

        if(empty($user)) {
            return response()->json([
                'message' => "not authorised"
            ], 403);
        }

        $user[$request->field] = $request->value;
        $user->save();

        return new UserResource($user);

    }


}
