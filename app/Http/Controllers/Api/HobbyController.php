<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\hobby\CreateHobbyRequest;
use App\Models\Hobby;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HobbyController extends Controller
{
    public function createHobby(CreateHobbyRequest $request) 
    {
        try {
            // dd($request->user()->id);
           $hobby = Hobby::create([
            'name'    => $request->name,
            'user_id' => auth()->user()->id
           ]);
            return response()->json([
                'status' => 'success',
                'hobby' => $hobby
            ], 200);
        }
        catch (ModelNotFoundException $e){
            return response()->json(["message" => "not create hobby"], 404);
        }
    }

    public function getHobbyes() 
    {
        try {
            $hobbyes = Hobby::where('user_id', auth()->user()->id)->get();
             return response()->json([
                 'status' => 'success',
                 'hobbyes' => $hobbyes
             ], 200);
         }
         catch (ModelNotFoundException $e){
             return response()->json(["message" => "hobbyes not found"], 404);
         }
    }

    public function deleteOffices($id) 
    {
        try {
            $hobby = Hobby::findOrFail($id)->delete();
             return response()->json([
                 'status' => 'success'
             ], 200);
         }
         catch (ModelNotFoundException $e){
             return response()->json(["message" => "hobby not found"], 404);
         }
    }
}
