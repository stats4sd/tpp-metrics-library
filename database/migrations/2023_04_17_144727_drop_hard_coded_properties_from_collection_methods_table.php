<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collection_methods', function (Blueprint $table) {
            $table->dropColumn(['equipment_notes',
                                'expertise_collection_notes',
                                'expertise_analysis_notes',
                                'cost_notes',
                                'time_to_collect_notes',
                                'sampling',
                                'timing_of_sampling',
                                'frequency_notes']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collection_methods', function (Blueprint $table) {
            $table->text('equipment_notes')->nullable();
            $table->text('expertise_collection_notes')->nullable();
            $table->text('expertise_analysis_notes')->nullable();
            $table->text('cost_notes')->nullable();
            $table->text('time_to_collect_notes')->nullable();
            $table->text('sampling')->nullable();
            $table->text('timing_of_sampling')->nullable();
            $table->text('frequency_notes')->nullable();
        });
    }
};
