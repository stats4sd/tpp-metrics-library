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
        Schema::create('scale_tool', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scale_id')->constrained('scales')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('unreviewed_import')->default(false);
            $table->text('relation_notes')->nullable();
            $table->text('type')->nullable()->comment('should be "measurement or reporting"');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scale_tool');
    }
};
