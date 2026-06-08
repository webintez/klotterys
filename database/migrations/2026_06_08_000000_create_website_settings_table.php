<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('qr_code')->nullable();
            $table->string('upi_id');
            $table->decimal('registration_fee', 10, 2)->default(3150.00);
            $table->string('bank_name')->default('State Bank of India');
            $table->string('bank_account_name')->default('Kerala State Lottery');
            $table->string('bank_account_no')->default('53845623856');
            $table->string('bank_ifsc')->default('SBIN0030466');
            $table->timestamps();
        });

        // Seed default record
        DB::table('website_settings')->insert([
            'qr_code' => 'images/qr_code.jpeg',
            'upi_id' => '9369873638-t50f@ybl',
            'registration_fee' => 3150.00,
            'bank_name' => 'State Bank of India',
            'bank_account_name' => 'Kerala State Lottery',
            'bank_account_no' => '53845623856',
            'bank_ifsc' => 'SBIN0030466',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
