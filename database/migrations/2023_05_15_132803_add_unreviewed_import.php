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
            $table->boolean('unreviewed_import')->default(0)->after('notes');
        });
        
        Schema::table('collection_methods', function (Blueprint $table) {
            $table->boolean('unreviewed_import')->default(0)->after('pros_cons');
        });

        Schema::table('geographies', function (Blueprint $table) {
            $table->boolean('unreviewed_import')->default(0)->after('notes');
        });

        Schema::table('scales', function (Blueprint $table) {
            $table->boolean('unreviewed_import')->default(0)->after('notes');
        });

        Schema::table('metrics', function (Blueprint $table) {
            $table->boolean('unreviewed_import')->default(0)->after('notes');
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
