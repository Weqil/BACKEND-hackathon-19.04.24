<?php

namespace App\Console\Commands;

use App\Mail\NotificationAboutCouple;
use App\Mail\NotificationAboutCoupleNotFound;
use App\Models\Company;
use App\Models\Meeting;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class FoundACouple extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:found-a-couple';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $weekDay = $this->getCurrentWeekDay();
        $companyWithMeetingInThisDayIds = $this->getCompaniesByMeetingWeekDay($weekDay)->pluck("id");

        foreach($companyWithMeetingInThisDayIds as $company_id){
            $this->spendOldNotAcceptedMeetings($company_id);
            $users = $this->getCompanyUsers($company_id)->toArray();
            shuffle($users);
            $splitedUsers = $this->splitUsers($users);
            // dd($splitedUsers);
            $this->coupleUsers($splitedUsers, $company_id);
        }
    }

    private function getCompaniesByMeetingWeekDay($weekDay){
        $companies = Company::where("meeting_week_day", $weekDay);
        return $companies;
    }

    private function coupleUsers($splitedUsers, $company_id){
        foreach($splitedUsers as $coupleUsers){
            if (count($coupleUsers) == 2){
                $meeting = Meeting::create([
                    "title" => "Встреча ".$coupleUsers[0]["name"]." с ".$coupleUsers[1]["name"],
                    "company_id" => $company_id
                ]);
                $meeting->users()->attach([$coupleUsers[0]["id"],$coupleUsers[1]["id"]]);
                // dd($coupleUsers[0]["email"]);

                try{
                    Mail::to($coupleUsers[0]['email'])->send(new NotificationAboutCouple());
                    Mail::to($coupleUsers[1]['email'])->send(new NotificationAboutCouple());
                }
                catch (Exception $e){

                }

            }
            else{
                Mail::to($coupleUsers[0]['email'])->send(new NotificationAboutCoupleNotFound());
            }
        }
    }

    public function spendOldNotAcceptedMeetings($company_id){
        $meetings = Company::find($company_id)->meetings;

        foreach($meetings as $meeting){
            $diffDays = $this->getDaysDifferent($meeting["created_at"]);
            if($meeting["status"] == null && $diffDays < -3){
                $meeting->update([
                    "status" => false
                ]);
            }
        }
    }

    private function getDaysDifferent($meetingCreatedDate){
        $meetingDate = Carbon::parse($meetingCreatedDate);
        $today = Carbon::today();

        return $today->diffInDays($meetingDate);
    }
    private function splitUsers($users){
        return array_chunk($users, 2);
    }

    private function getCompanyUsers($company_id){
        $users = Company::find($company_id)->users;
        return $users;
    }

    private function getCurrentWeekDay(){
        return date("l");
    }
}
