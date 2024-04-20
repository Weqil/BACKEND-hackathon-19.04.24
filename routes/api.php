<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\isCompanyOwner;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(UserController::class)->group(function () {
    Route::get("users/me", "getMe")->middleware("auth:sanctum");
});

Route::controller(CompanyController::class)->group(function() {
    Route::patch("companies/{company_id}", "update")->middleware(['auth:sanctum',isCompanyOwner::class]);
    Route::post("companies/{company_id}/invites", "createInviteCode")->middleware(['auth:sanctum',isCompanyOwner::class]);
    Route::get("companies/{company_id}/invites", "getAllInviteCodes")->middleware(['auth:sanctum',isCompanyOwner::class]);
    Route::delete("companies/{company_id}/invites/{invite_id}", "deleteCompanyInviteCode")->middleware(['auth:sanctum',isCompanyOwner::class]);
    Route::get("companies/{company_id}/users", "getAllUsers")->middleware(['auth:sanctum',isCompanyOwner::class]);
});
