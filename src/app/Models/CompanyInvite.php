<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInvite extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
