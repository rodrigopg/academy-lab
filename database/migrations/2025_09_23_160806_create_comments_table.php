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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('parent_id')->nullable()->constrained('comments');
            $table->foreignId('product_course_id')->nullable()->constrained('product_course');
            $table->text('content');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
