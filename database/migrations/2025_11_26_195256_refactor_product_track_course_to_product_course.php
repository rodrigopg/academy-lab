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
        // Rename table from product_track_course to product_course
        Schema::rename('product_track_course', 'product_course');

        // Remove track_id column as we're making direct product -> course relationship
        Schema::table('product_course', function (Blueprint $table) {
            $table->dropForeign(['track_id']);
            $table->dropIndex(['product_id', 'track_id', 'course_id']);
            $table->dropColumn('track_id');

            // Add unique constraint for product_id + course_id
            $table->unique(['product_id', 'course_id']);
        });

        // Update foreign key references in related tables
        Schema::table('lesson_statuses', function (Blueprint $table) {
            $table->dropForeign(['product_track_course_id']);
            $table->renameColumn('product_track_course_id', 'product_course_id');
            $table->foreign('product_course_id')->references('id')->on('product_course')->nullOnDelete();
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['product_track_course_id']);
            $table->renameColumn('product_track_course_id', 'product_course_id');
            $table->foreign('product_course_id')->references('id')->on('product_course')->nullOnDelete();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['product_track_course_id']);
            $table->renameColumn('product_track_course_id', 'product_course_id');
            $table->foreign('product_course_id')->references('id')->on('product_course')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore foreign key references in related tables
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['product_course_id']);
            $table->renameColumn('product_course_id', 'product_track_course_id');
            $table->foreign('product_track_course_id')->references('id')->on('product_track_course')->nullOnDelete();
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['product_course_id']);
            $table->renameColumn('product_course_id', 'product_track_course_id');
            $table->foreign('product_track_course_id')->references('id')->on('product_track_course')->nullOnDelete();
        });

        Schema::table('lesson_statuses', function (Blueprint $table) {
            $table->dropForeign(['product_course_id']);
            $table->renameColumn('product_course_id', 'product_track_course_id');
            $table->foreign('product_course_id')->references('id')->on('product_track_course')->nullOnDelete();
        });

        // Restore product_course table structure
        Schema::table('product_course', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'course_id']);
            $table->foreignId('track_id')->after('product_id')->constrained('tracks');
            $table->index(['product_id', 'track_id', 'course_id']);
        });

        // Rename table back
        Schema::rename('product_course', 'product_track_course');
    }
};
