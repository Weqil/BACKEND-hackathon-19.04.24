<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function invites() {
        return $this->hasMany(CompanyInvite::class);
    }
}
