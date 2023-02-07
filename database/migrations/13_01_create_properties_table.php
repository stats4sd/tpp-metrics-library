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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('definition')->nullable();
            $table->string('default_type')->nullable()->comment('Should this be added by default to new instances of a metric / collection method / other? This value should be a model classFQN');
            $table->boolean('editable_options')->default(false)->comment('Can the user add/edit options for this property?');
            $table->boolean('select_multiple')->default(true)->comment('Can the user select multiple options for this property?');
            $table->boolean('free_text')->default(true)->comment('Does the field include a free-text field for notes? (Note, this can be selected *as well as* select options.');
            $table->boolean('select_options')->default(true)->comment('Does the property have property_options for the user to select from?');
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
        Schema::dropIfExists('properties');
    }
};
