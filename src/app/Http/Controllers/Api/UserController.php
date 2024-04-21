<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function getMe(Request $request){
        $user = User::find(auth()->user()->id)->with("companies","hobbies")->first();

        return response()->json([
            "user" => $user
        ]);
    }

    public function getMeetings() 
    {
        try{
            $meetings = User::find(auth()->user()->id)->meetings()->get();

            return response()->json(["users_meetings" => $meetings]);
        }

        catch (ModelNotFoundException $e){
            return response()->json(["message" => "meetings not found"], 404);
        }
    }

    public function getMeetingsCount() 
    {
        try{
            $meetings = User::find(auth()->user()->id)->meetings()->count();

            return response()->json(["users_meetings_count" => $meetings]);
        }

        catch (ModelNotFoundException $e){
            return response()->json(["message" => "meetings not found"], 404);
        }
    }
}
