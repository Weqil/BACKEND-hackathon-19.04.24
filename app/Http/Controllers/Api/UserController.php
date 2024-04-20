<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getMe(Request $request){
        $user = User::find(auth()->user()->id)->with("companies","hobbies")->first();

        return response()->json([
            "user" => $user
        ]);
    }
}
