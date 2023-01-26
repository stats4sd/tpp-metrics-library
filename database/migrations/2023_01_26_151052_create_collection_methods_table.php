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
            $table->string('equipment')->comments('fixed options')->nullable();
            $table->text('equipment_notes')->nullable();
            $table->string('expertise_collection')->comments('fixed_option')->nullable();
            $table->text('expertise_collection_notes')->nullable();
            $table->string('expertise_analysis')->nullable();
            $table->text('expertise_analysis_notes')->nullable();

            $table->string('cost')->nullable();
            $table->text('cost_notes')->nullable();

            $table->foreignId('time_to_collect_id')->nullable()->constrained('frequencies')->cascadeOnUpdate()->nullOnDelete();
            $table->text('time_to_collect_notes')->nullable();

            $table->text('sampling')->nullable();
            $table->text('timing_of_sampling')->nullable();

            $table->string('frequency_id')->nullable()->constrained('frequencies')->cascadeOnUpdate()->nullOnDelete();;
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
