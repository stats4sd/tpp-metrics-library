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
        Schema::table('metric_dimension', function (Blueprint $table) {
            $table->boolean('needs_review')->default(0)->after('relation_notes');
        });

        Schema::table('metric_collection_method', function (Blueprint $table) {
            $table->boolean('needs_review')->default(0)->after('relation_notes');
        });

        Schema::table('metric_geography', function (Blueprint $table) {
            $table->boolean('needs_review')->default(0)->after('relation_notes');
        });

        Schema::table('metric_scale', function (Blueprint $table) {
            $table->boolean('needs_review')->default(0)->after('relation_notes');
        });

        Schema::table('metric_tool', function (Blueprint $table) {
            $table->boolean('needs_review')->default(0)->after('relation_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metric_dimension', function (Blueprint $table) {
            $table->dropColumn('needs_review');
        });

        Schema::table('metric_collection_method', function (Blueprint $table) {
            $table->dropColumn('needs_review');
        });

        Schema::table('metric_geography', function (Blueprint $table) {
            $table->dropColumn('needs_review');
        });

        Schema::table('metric_scale', function (Blueprint $table) {
            $table->dropColumn('needs_review');
        });

        Schema::table('metric_tool', function (Blueprint $table) {
            $table->dropColumn('needs_review');
        });
    }
};
