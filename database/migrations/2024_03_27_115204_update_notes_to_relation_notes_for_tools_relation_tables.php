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
        // framework_tool, metric_tool, indication_selection_tool and scale_tool already have relation_notes instead of notes.
        // update other tool relation tables to have relation_notes instead of notes.

        Schema::table('dimension_tool', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('country_tool', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('data_collection_tool', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('data_type_tool', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('developer_tool', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('framing_tool', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('metric_user_tool', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('theme_tool', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('sdg_tool', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('relation_notes_for_tools_relation_tables', function (Blueprint $table) {
            //
        });
    }
};
