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
        Schema::table('discussion_points', function (Blueprint $table) {
            $table->foreignId('property_value_id')->nullable()->comments('useful for points about many-many relationships on a specific related entity');
             $table->string('property_value_type')->nullable()->comments('useful for points about many-many relationships on a specific related entity.');
            $table->text('decision')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discussion_points', function (Blueprint $table) {
            //
        });
    }
};
