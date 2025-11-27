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
        Schema::create('product_course', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->index();
            $table->foreignId('course_id')->constrained('courses');
            $table->integer('position');
            $table->enum('visibility', ['visible', 'hidden'])->default('visible');
            $table->timestamps();

            $table->unique(['product_id', 'course_id']);
            $table->index(['product_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_course');
    }
};
