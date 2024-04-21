<?php

namespace App\Services;

use App\Models\CompanyInvite;
use App\Models\User;
use Exception;

class AuthService {
    public function registerAsUser($email, $pass, $name, $code) {
        $company = $this->getCompanyByIviteCodeIfIsExists($code);
        if($company){
            $user = $this->createUserWithProfile($email, $pass, $name);
            $this->addUserToCompany($user, $company);
            return $user;
        }
        else{
            throw new Exception("code not found");
        }
    }

    public function registerAsCompany($email, $pass, $name, $companyName) {
        $user = $this->createUserWithProfile($email, $pass, $name);
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

    private function createUserWithProfile($email, $pass, $name){
        $user  =  User::create([
            'name'=> $name,
            'password'=> $pass,
            'email' => $email,
        ]);

        $user->profile()->create();

        return $user;
    }

    private function addUserToCompany($user, $company){
        $company->users()->attach($user->id);
    }
}
