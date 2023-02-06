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
        Schema::create('discussion_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->comment('The id of the item to be discussed');
            $table->string('subject_type')->comment('The model type of the item to be discussed');
            $table->string('property')->comment('The property of the item to be discussed')->nullable();
            $table->text('notes')->nullable()->comment('Notes about the query / discussion point to make');
            $table->foreignId('user_id')->comment('The user who created the discussion poihnt');
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
        Schema::dropIfExists('discussion_points');
    }
};
