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
        Schema::create('product_track_course', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->index();
            $table->foreignId('track_id')->constrained('tracks');
            $table->foreignId('course_id')->constrained('courses');
            $table->integer('position');
            $table->enum('visibility', ['visible', 'hidden'])->default('visible');
            $table->timestamps();

            $table->index(['product_id', 'track_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_track_course');
    }
};
