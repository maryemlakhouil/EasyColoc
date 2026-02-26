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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('colocation_id')->constrained()->cascadeOnDelete();

            $table->string('invited_email');
            $table->string('token')->unique();
            $table->enum('status', ['pending', 'accepted', 'refused', 'expired'])->default('pending');
            $table->timestamp('expires_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
