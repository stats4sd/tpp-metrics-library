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

        Schema::dropIfExists('metric_topic');
        Schema::dropIfExists('metric_sub_dimension');
        Schema::dropIfExists('metric_farming_system');

        Schema::table('dimensions', function (Blueprint $table) {
            $table->dropForeign('dimensions_topic_id_foreign');
            $table->dropColumn('topic_id');
        });

        Schema::dropIfExists('topics');
        Schema::dropIfExists('sub_dimensions');
        Schema::dropIfExists('farming_systems');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
