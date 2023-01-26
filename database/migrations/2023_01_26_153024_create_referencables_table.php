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
        Schema::create('referencable', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reference_id');
            $table->string('reference_type')->comment('could be:
                - collection method
                - metric - data source
                - metric - computation guidance
                - metric - references
            ');

            $table->string('referencable_type');
            $table->foreignId('referencable_id');
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
        Schema::dropIfExists('documentables');
    }
};
