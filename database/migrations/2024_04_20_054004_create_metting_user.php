<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metting_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId("meeting_id")->constrained("meetings");
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("company_id")->constrained("companies");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metting_user');
    }
};