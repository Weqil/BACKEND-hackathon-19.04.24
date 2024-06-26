<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Meeting;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function createInviteCode(Request $request, $company_id){
        try {
            $company = Company::findOrFail($company_id);
            $code = uniqid();
            $company->invites()->create([
                "code" => $code
            ]);

            return response()->json(["code"=>$code]);
        }
        catch (ModelNotFoundException $e){
            return response()->json(["message" => "company not found"], 404);
        }
    }

    public function getAllInviteCodes(Request $request, $company_id){
        try {
            $company = Company::findOrFail($company_id);
            $companyInviteCodes = $company->invites;

            return response()->json(["company_invites" => $companyInviteCodes]);
        }
        catch (ModelNotFoundException $e){
            return response()->json(["message" => "company not found"], 404);
        }
    }

    public function getAllUsers(Request $request, $company_id){
        try{
            $company = Company::findorFail($company_id);
            $companyUsers = $company->users;

            return response()->json(["company_users" => $companyUsers]);
        }

        catch (ModelNotFoundException $e){
            return response()->json(["message" => "company not found"], 404);
        }
    }

    public function getAllMeetings(Request $request, $company_id){
        try{
            $company = Company::findorFail($company_id);
            $companyMeetings = $company->meetings;

            return response()->json(["company_meetings" => $companyMeetings]);
        }

        catch (ModelNotFoundException $e){
            return response()->json(["message" => "company not found"], 404);
        }
    }

    public function getAllUsersCount($company_id){
        try{
            $company = Company::findorFail($company_id);
            $companyUsers = $company->users->count();

            return response()->json(["company_users_count" => $companyUsers]);
        }

        catch (ModelNotFoundException $e){
            return response()->json(["message" => "company not found"], 404);
        }
    }

    public function getAllMeetingsCount($company_id){
        try{
            $company = Company::findorFail($company_id);
            $companyMeetings = $company->meetings->count();

            return response()->json(["company_meentings_count" => $companyMeetings]);
        }

        catch (ModelNotFoundException $e){
            return response()->json(["message" => "company not found"], 404);
        }
    }

    public function update(Request $request, $company_id) {

        $name = $request->get("name");
        $meetingWeekDay = $request->get("meeting_week_day");

        $data = [];

        if($name){
            $data["name"] = $name;
        }
        if($meetingWeekDay){
            $data["meeting_week_day"] = $meetingWeekDay;
        }

        $company = Company::findOrFail($company_id);
        if(!empty($data)){
            $company->update($data);

            return response()->json(["message" => "Company updated"]);
        }

        return response()->json(["message" => "Nothing update"]);

    }

    public function deleteUser(Request $request, $company_id, $user_id){
        $user = Company::find($company_id)->users()->where("users.id", $user_id)->first();
        $user->meetings()->detach();
        $user->companies()->detach();
        $user->delete();

        return response()->json(["message" => "User deleted"]);
    }

    public function deleteCompanyInviteCode(Request $request, $company_id, $invite_id){
        try{
            $invite = Company::findOrFail($company_id)->invites()->where("id", $invite_id);
            $invite->delete();

            return response()->json(["message" => "Invite deleted"]);
        }
        catch(ModelNotFoundException $e){
            return response()->json(["message" => "Company or Invite not found"], 404);
        }
    }
}
