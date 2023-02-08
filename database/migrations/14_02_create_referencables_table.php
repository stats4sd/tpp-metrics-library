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
        Schema::create('referencables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reference_id');
            $table->string('referencable_type')->comment('The model type this reference is linked to');
            $table->foreignId('referencable_id')->comment('The model ID this reference is linked to');
            $table->string('reference_type')->comment('The reference type (.e.g data source, computation guidance, reference)');
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
        Schema::dropIfExists('references');
    }
};
