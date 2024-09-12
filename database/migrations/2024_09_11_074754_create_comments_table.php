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
        Schema::create('comments', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('body');
            $table->timestamps();
            $table->string('slug');
            $table->string('author_id');
            $table->foreign('slug')->references('slug')->on('articles')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
