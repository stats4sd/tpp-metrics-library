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

        // METRIC PIVOT TABLES

        Schema::table('metric_parent_child', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('metric_dimension', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
        Schema::table('metric_farming_system', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
        Schema::table('metric_framework', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
        Schema::table('metric_geography', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
        Schema::table('metric_metric', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
        Schema::table('metric_metric_user', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('metric_property_options', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
        Schema::table('metric_scale', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
        Schema::table('metric_sub_dimension', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
        Schema::table('metric_tool', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
        Schema::table('metric_topic', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
        Schema::table('metric_unit', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
        Schema::table('metric_users', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });


        // OTHER PIVOT TABLES

        Schema::table('framework_tool', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('property_links', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('property_option_property_link', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });

        Schema::table('referencables', function (Blueprint $table) {
            $table->renameColumn('notes', 'relation_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('metric_dimension', function (Blueprint $table) {
//            $table->renameColumn('relation_notes', 'notes');
//        });
//        Schema::table('metric_farming_system', function (Blueprint $table) {
//            $table->renameColumn('relation_notes', 'notes');
//        });
//        Schema::table('metric_framework', function (Blueprint $table) {
//            $table->renameColumn('relation_notes', 'notes');
//        });
//        Schema::table('metric_geography', function (Blueprint $table) {
//            $table->renameColumn('relation_notes', 'notes');
//        });
//        Schema::table('metric_metric', function (Blueprint $table) {
//            $table->renameColumn('relation_notes', 'notes');
//        });
//        Schema::table('metric_metric_user', function (Blueprint $table) {
//            $table->renameColumn('relation_notes', 'notes');
//        });
        Schema::table('metric_parent_child', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });
        Schema::table('metric_property_options', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });
        Schema::table('metric_scale', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });
        Schema::table('metric_sub_dimension', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });
        Schema::table('metric_tool', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });
        Schema::table('metric_topic', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });
        Schema::table('metric_unit', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });
        Schema::table('metric_users', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });

        Schema::table('framework_tool', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });

        Schema::table('property_links', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });

        Schema::table('property_option_property_link', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });

        Schema::table('referencables', function (Blueprint $table) {
            $table->renameColumn('relation_notes', 'notes');
        });
    }
};
