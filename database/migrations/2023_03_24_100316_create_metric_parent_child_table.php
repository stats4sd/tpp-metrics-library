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
        Schema::create('metric_parent_child', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->references('id')->on('metrics')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('child_id')->references('id')->on('metrics')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('metric_parent_child');
    }
};
