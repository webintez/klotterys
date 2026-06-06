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
            $table->string('prize_category')->default('1st Prize')->after('winning_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('draw_results', function (Blueprint $table) {
            $table->dropColumn('prize_category');
        });
    }
};
