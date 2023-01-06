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
        Schema::create('metric_method', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metric_id')->constrained('metrics')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('method_id')->constrained('methods')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('metric_method');
    }
};
