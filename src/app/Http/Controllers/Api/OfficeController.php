<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\office\CreateOfficeRequest;
use App\Models\Office;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OfficeController extends Controller
{
    public function createOffice(CreateOfficeRequest $request) 
    {
        try {
            // dd($request->user()->id);
           $office = Office::create([
            'name'    => $request->name,
            'user_id' => auth()->user()->id
           ]);
            return response()->json([
                'status' => 'success',
                'office' => $office
            ], 200);
        }
        catch (ModelNotFoundException $e){
            return response()->json(["message" => "not create office"], 404);
        }
    }

    public function getOffices() 
    {
        try {
            $office = Office::where('user_id', auth()->user()->id)->get();
             return response()->json([
                 'status' => 'success',
                 'offices' => $office
             ], 200);
         }
         catch (ModelNotFoundException $e){
             return response()->json(["message" => "offices not found"], 404);
         }
    }

    public function deleteOffices($id) 
    {
        try {
            $office = Office::findOrFail($id)->delete();
             return response()->json([
                 'status' => 'success'
             ], 200);
         }
         catch (ModelNotFoundException $e){
             return response()->json(["message" => "office not found"], 404);
         }
    }
}
