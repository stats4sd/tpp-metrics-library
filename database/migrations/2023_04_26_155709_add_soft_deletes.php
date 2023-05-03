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
        Schema::table('dimensions', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('collection_methods', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('farming_systems', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('frameworks', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('geographies', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('metric_users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('scales', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('sub_dimensions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('tools', function (Blueprint $table) {
            $table->softDeletes();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dimensions', function (Blueprint $table) {
            //
        });
    }
};
