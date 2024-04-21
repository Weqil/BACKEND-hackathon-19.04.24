<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use Illuminate\Console\Command;

class AddUsersToCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-users-to-company {company_id}';

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
        $company_id = $this->argument("company_id");
        $company = Company::find($company_id);
        $users  = User::factory()->count(100)->create();
        $this->addUsersToCompany($users, $company);
    }

    private function addUsersToCompany($users, $company){
        $company->users()->attach($users);
    }
}
