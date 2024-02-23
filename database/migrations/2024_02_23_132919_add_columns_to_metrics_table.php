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
        Schema::table('metrics', function (Blueprint $table) {
            $table->string('system_interest')->nullable()->after('unreviewed_import')->comment('Does it measure the state of the system of interest or a pressure on the system of interest (i.e. external to system)? (options = state; pressure; NA; don\'t know)');
            $table->string('require_assessment')->nullable()->after('system_interest')->comment('Does it require assessment of change or a comparison? (options = yes; no; don\'t know; NA)');
            $table->string('based')->nullable()->after('require_assessment')->comment('Do the authors see the metric as being practice-based or performance based? (based on the perspective of the authors/assessment) (Options = practice; performance; don\'t know; NA)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metrics', function (Blueprint $table) {
            //
        });
    }
};
