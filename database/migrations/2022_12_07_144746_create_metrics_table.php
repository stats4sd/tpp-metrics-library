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
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();

            // 1.f - derived+related metrics
            $table->foreignId('parent_id')->nullable()->constrained('metrics')->nullOnDelete()->cascadeOnUpdate();
            $table->text('parent_child_notes')->nullable();

            // 0 - metric information
            $table->string('title');
            $table->foreignId('developer_id');

            // 1 - Metric Description
            $table->text('definition')->nullable();
            $table->text('concept')->nullable();
            $table->text('scale_notes')->nullable();
            $table->text('units_of_measure_notes')->change();

            $table->text('notes')->nullable();

            // 4 Data
            $table->foreignId('data_availability_id')->nullable();
            $table->text('data_availability_notes')->nullable();

            // 5.a
            $table->text('pros_cons')->nullable();

            // 5.b
            $table->text('copmutation_methods')->nullable();

            // 5.c
            $table->boolean('scalable')->nullable();
            $table->text('scalable_notes')->nullable();

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
        Schema::dropIfExists('metrics');
    }
};
