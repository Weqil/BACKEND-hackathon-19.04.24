<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(CompanyController::class)->group(function() {
    Route::post("companies/{company_id}/invites", "createInviteCode");
    Route::get("companies/{company_id}/invites", "getAllInviteCodes");
    Route::delete("companies/{company_id}/invites/{invite_id}", "deleteCompanyInviteCode");
    Route::get("companies/{company_id}/users", "getAllUsers");
});
