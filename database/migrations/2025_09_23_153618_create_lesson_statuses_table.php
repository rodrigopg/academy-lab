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
        Schema::create('lesson_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('product_track_course_id')->nullable()->constrained('product_track_course');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['lesson_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_statuses');
    }
};
