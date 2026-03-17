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
        Schema::create('questions', function (Blueprint $table) {
            $table->id(); // Primary key (auto-increment big integer)

            // Core references
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('test_series_id');
            $table->unsignedBigInteger('test_id');

            // question_id is a string like "q1762681205289"
            $table->string('question_id');

            // Question details
            $table->integer('position')->default(0);
            $table->string('questionType')->nullable();      // e.g., MCQ, True/False, etc.
            $table->string('questionTitle');                 // short title
            $table->longText('description')->nullable();     // full question text
            $table->json('options')->nullable();             // store options as JSON
            $table->string('imageUrl')->nullable();          // optional image
            $table->string('answer')->nullable();            // correct answer
            $table->text('note')->nullable();                // explanation or note

            // Flags
            $table->boolean('is_removed')->default(0);
            $table->timestamps(); // created_at & updated_at

            // ✅ Prevent duplicate questions within the same test
            $table->unique(['question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
