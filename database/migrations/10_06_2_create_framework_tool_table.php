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
        Schema::create('framework_tool', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_id')->constrained('frameworks')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('framework_tool');
    }
};
