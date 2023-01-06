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
        Schema::create('alt_names', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metric_id')->constrained('metrics')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->text('notes')->nullable();;
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
        Schema::dropIfExists('alt_names');
    }
};
