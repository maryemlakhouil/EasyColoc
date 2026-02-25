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
        Schema::create('colocation_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('colocation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('status', ['pending', 'accepted'])->default('pending');
            $table->timestamp('left_at')->nullable();
            $table->unique(['colocation_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colocation_user');
    }
};

return new class extends Migration {
    public function up(): void
    {
        Schema::create('colocation_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colocation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('status', ['pending', 'accepted'])->default('pending');
            $table->timestamp('left_at')->nullable();

            $table->timestamps();

            $table->unique(['colocation_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colocation_user');
    }
};