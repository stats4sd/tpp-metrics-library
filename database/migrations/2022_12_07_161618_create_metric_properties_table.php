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
        Schema::create('metric_properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('definition')->nullable();
            $table->boolean('editable_options')->default(false)->comment('Can the user add/edit options for this property?');
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
        Schema::dropIfExists('metric_properties');
    }
};
