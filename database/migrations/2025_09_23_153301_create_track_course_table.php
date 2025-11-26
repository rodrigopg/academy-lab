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
        Schema::create('track_course', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->constrained('tracks');
            $table->foreignId('course_id')->constrained('courses');
            $table->integer('position');
            $table->enum('visibility', ['visible', 'hidden'])->default('visible');
            $table->timestamps();

            $table->index(['track_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_course');
    }
};
