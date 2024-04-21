<?php

namespace App\Services;

use App\Models\CompanyInvite;
use App\Models\User;
use Exception;

class AuthService {
    public function registerAsUser($email, $pass, $name, $code, $social_url) {
        $company = $this->getCompanyByIviteCodeIfIsExists($code);
        if($company){
            $user = $this->createUserWithProfile($email, $pass, $name, $social_url);
            $this->addUserToCompany($user, $company);
            return $user;
        }
        else{
            throw new Exception("code not found");
        }
    }

    public function registerAsCompany($email, $pass, $name, $companyName) {
        $user = $this->createUserWithProfile($email, $pass, $name, $social_url = null);
            $company = $user->companies()->create([
                "name" => $companyName,
                "user_id" => $user->id
            ]);

        return $user;
    }

    private function getCompanyByIviteCodeIfIsExists($code){
        $inviteCode = CompanyInvite::where("code", $code)->first();
        if($inviteCode){
            $company = $inviteCode->company;
            return $company;
        }
        return false;
    }

    private function createUserWithProfile($email, $pass, $name, $social_url){
        $user  =  User::create([
            'name'=> $name,
            'password'=> $pass,
            "social_url" => $social_url,
            'email' => $email,
        ]);

        $user->profile()->create();

        return $user;
    }

    private function addUserToCompany($user, $company){
        $company->users()->attach($user->id);
    }
}
