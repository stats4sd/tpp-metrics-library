<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_methods', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('pros_cons')->nullable();
            $table->text('equipment_notes')->nullable();
            $table->text('expertise_collection_notes')->nullable();
            $table->text('expertise_analysis_notes')->nullable();
            $table->text('cost_notes')->nullable();
            $table->text('time_to_collect_notes')->nullable();
            $table->text('sampling')->nullable();
            $table->text('timing_of_sampling')->nullable();
            $table->text('frequency_notes')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_methods');
    }
};
