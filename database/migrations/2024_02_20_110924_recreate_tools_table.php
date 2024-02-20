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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tools');

        Schema::create('tools', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('definition')->nullable();
            $table->text('notes')->nullable();

            $table->string('reviewer_name')->nullable();
            $table->string('acronym')->nullable();
            $table->text('web_ref')->nullable();
            $table->string('author')->nullable();
            $table->integer('year_published')->nullable();

            $table->boolean('updated')->nullable();
            $table->integer('year_updated')->nullable();
            $table->text('updated_ref')->nullable();
            $table->boolean('promoted_tool')->nullable();
            $table->boolean('specify_indicators')->nullable();

            $table->boolean('wider_use')->nullable();
            $table->text('wider_use_evidence')->nullable();
            $table->text('wider_use_notes')->nullable();
            $table->boolean('adapted')->nullable();
            $table->text('adapted_ref')->nullable();

            $table->text('framing_definition')->nullable();
            $table->boolean('framing_indicator_link')->nullable();
            $table->string('indicator_convenience')->nullable();
            $table->string('sustainability_view')->nullable();
            $table->string('tool_orientiation')->nullable();

            $table->string('localisable')->nullable();
            $table->string('system_type')->nullable();
            $table->boolean('visualise_framework')->nullable();
            $table->string('intended_function')->nullable();
            $table->string('comparison_type')->nullable();

            $table->boolean('verifiable')->nullable();
            $table->boolean('local_indicators')->nullable();
            $table->boolean('stakeholder_involved')->nullable();
            $table->string('complexity')->nullable();
            $table->string('access')->nullable();

            $table->boolean('paid_access')->nullable();
            $table->boolean('online_platform')->nullable();
            $table->boolean('guide_assess')->nullable();
            $table->boolean('guide_analysis')->nullable();
            $table->boolean('guide_interpret')->nullable();

            $table->boolean('guide_data_gov')->nullable();
            $table->boolean('informed_consent')->nullable();
            $table->boolean('visualise_result')->nullable();
            $table->string('visualise_type')->nullable();
            $table->string('assessment_results')->nullable();

            $table->string('metric_no')->nullable();
            $table->string('collection_time')->nullable();
            $table->string('interval')->nullable();
            $table->boolean('interaction')->nullable();
            $table->text('interaction_expl')->nullable();

            $table->boolean('scaleable')->nullable();
            $table->boolean('aggregation')->nullable();
            $table->boolean('weighting')->nullable();
            $table->text('weighting_preference')->nullable();
            $table->text('comments')->nullable();

            $table->string('once_multi')->nullable();
            $table->boolean('metric_eval')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tools');
    }
};
