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
        Schema::create('follows', function (Blueprint $table) {
            $table->string('follower_id');
            $table->string('followee_id');
            $table->primary(['follower_id', 'followee_id']);
            $table->foreign('follower_id')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('followee_id')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
