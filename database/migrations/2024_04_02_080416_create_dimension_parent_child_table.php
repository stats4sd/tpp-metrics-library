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
        Schema::create('dimension_parent_child', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->references('id')->on('dimensions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('child_id')->references('id')->on('dimensions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('relation_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dimension_parent_child');
    }
};
