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
        Schema::create('metric_topic', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('dimensions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('metric_id')->constrained('metrics')->onDelete('cascade')->onUpdate('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dimension_metric');
    }
};
