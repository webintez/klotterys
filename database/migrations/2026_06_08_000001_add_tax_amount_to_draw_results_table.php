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
            $table->string('tax_amount')->nullable()->after('winning_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('draw_results', function (Blueprint $table) {
            $table->dropColumn('tax_amount');
        });
    }
};
