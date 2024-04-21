<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function accept(Request $request,$meeting_id){
        $meeting = Meeting::find($meeting_id);

        $meeting->update([
            "status" => true,
            "start_time" => $request->get("start_time")
        ]);

        return response()->json(["message"=>"meeting accepted"]);
    }

    public function decline(Request $request, $meeting_id){
        $meeting = Meeting::find($meeting_id);

        $meeting->update([
            "status" => false,
        ]);

        return response()->json(["message"=>"meeting declined"]);
    }
}
