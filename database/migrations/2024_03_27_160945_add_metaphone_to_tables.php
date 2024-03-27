<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('collection_methods', function (Blueprint $table) {
            $table->string('metaphone')->after('title')->index()->nullable();
        });

        Schema::table('dimensions', function (Blueprint $table) {
            $table->string('metaphone')->after('name')->index()->nullable();
        });

        Schema::table('scales', function (Blueprint $table) {
            $table->string('metaphone')->after('name')->index()->nullable();
        });

        Schema::table('tools', function (Blueprint $table) {
            $table->string('metaphone')->after('name')->index()->nullable();
        });

        Schema::table('frameworks', function (Blueprint $table) {
            $table->string('metaphone')->after('name')->index()->nullable();
        });

        Schema::table('units', function (Blueprint $table) {
            $table->string('metaphone')->after('name')->index()->nullable();
        });

        Schema::table('metric_users', function (Blueprint $table) {
            $table->string('metaphone')->after('name')->index()->nullable();
        });

        Schema::table('geographies', function (Blueprint $table) {
            $table->string('metaphone')->after('name')->index()->nullable();
        });

        Schema::table('themes', function (Blueprint $table) {
            $table->string('metaphone')->after('name')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
