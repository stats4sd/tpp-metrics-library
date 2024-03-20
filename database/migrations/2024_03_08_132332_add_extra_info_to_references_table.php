<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('references', function (Blueprint $table) {

            $table->renameColumn('name', 'title');
            $table->year('year')->nullable();
            $table->text('journal')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('issue')->nullable();
            $table->text('pages')->nullable();
            $table->text('authors')->nullable();
            $table->text('language')->nullable();
            $table->text('publisher')->nullable();
            $table->text('location')->nullable();
            $table->text('abstract')->nullable();
            $table->text('keywords')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('references', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('journal');
            $table->dropColumn('volume');
            $table->dropColumn('issue');
            $table->dropColumn('pages');
            $table->dropColumn('authors');
            $table->dropColumn('language');
            $table->dropColumn('publisher');
            $table->dropColumn('location');
            $table->dropColumn('abstract');
            $table->dropColumn('keywords');
        });
    }
};
