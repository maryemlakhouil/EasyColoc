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
        Schema::create('depences', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->foreignId('payer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('colocation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depences');
    }
};
