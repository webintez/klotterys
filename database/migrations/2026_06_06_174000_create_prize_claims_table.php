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
        Schema::create('prize_claims', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number');
            $table->string('mobile');
            $table->decimal('registration_fee', 10, 2)->default(3260.00);
            $table->string('screenshot');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prize_claims');
    }
};
