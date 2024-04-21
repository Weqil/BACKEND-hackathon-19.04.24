<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getMe(Request $request){
        $user = User::where("id",auth()->user()->id)->with("companies","hobbies", "profile")->firstOrFail();

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

    public function getUserById(Request $request, $user_id){
        return response()->json(["user" => User::where("id", $user_id)->with("profile", "hobbies", "offices")->firstOrFail()]);
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
