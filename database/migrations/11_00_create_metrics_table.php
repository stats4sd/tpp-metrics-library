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
            $table->foreignId('developer_id')->nullable();

            // 1 - Metric Description
            $table->text('definition')->nullable();
            $table->text('concept')->nullable();
            $table->text('scale_notes')->nullable();
            $table->text('units_of_measure_notes')->change();

            $table->text('notes')->nullable();

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
