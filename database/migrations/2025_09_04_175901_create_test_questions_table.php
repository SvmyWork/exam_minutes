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
        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('test_series_id')->nullable();
            $table->string('test_series_name')->nullable();
            $table->unsignedBigInteger('test_id')->nullable();
            $table->string('test_name')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('section_name')->nullable();
            $table->unsignedBigInteger('question_id')->nullable();
            $table->text('question_text')->nullable();
            $table->enum('question_type', ['mcq', 'true_false', 'fill_blank', 'subjective'])->default('mcq');
            $table->json('options')->nullable(); // store multiple choices
            $table->json('correct_answer')->nullable(); // can store single/multiple answers
            $table->decimal('marks', 5, 2)->default(1.00);
            $table->decimal('negative_marks', 5, 2)->default(0.00);
            $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->default('medium');
            $table->longText('explanation')->nullable();
            $table->timestamps();

            // Example of adding foreign keys (optional if you already have those tables)
            // $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            // $table->foreign('test_series_id')->references('id')->on('test_series')->onDelete('cascade');
            // $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');
            // $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_questions');
    }
};
