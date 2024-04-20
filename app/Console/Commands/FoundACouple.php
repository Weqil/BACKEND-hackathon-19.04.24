<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Console\Command;

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
            }
            else{
                echo "sorry ".$coupleUsers[0]["name"];
            }
        }
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
