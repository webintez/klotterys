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
        Schema::table('draw_results', function (Blueprint $table) {
            $table->string('winning_amount')->nullable()->after('prize_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('draw_results', function (Blueprint $table) {
            $table->dropColumn('winning_amount');
        });
    }
};
