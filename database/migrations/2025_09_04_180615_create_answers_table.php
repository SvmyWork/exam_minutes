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
        Schema::create('answers', function (Blueprint $table) {
            $table->id('answer_id'); // Primary key
            $table->unsignedBigInteger('test_series_id');
            $table->unsignedBigInteger('test_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('question_id');
            $table->string('marked_option')->nullable(); // A/B/C/D or submit/autosubmit
            $table->integer('time_taken')->default(0); // in seconds
            $table->integer('time_remaining')->default(0); // in seconds
            $table->timestamp('action_timestamp')->useCurrent();
            $table->string('session_id'); // attempt session identifier
            $table->enum('status', ['in_progress', 'submitted', 'autosubmitted'])->default('in_progress');
            $table->timestamp('last_visited')->nullable();
            $table->timestamps();

            // Foreign keys (optional, uncomment if related tables exist)
            // $table->foreign('test_series_id')->references('id')->on('test_series')->onDelete('cascade');
            // $table->foreign('test_id')->references('test_id')->on('tests')->onDelete('cascade');
            // $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            // $table->foreign('question_id')->references('question_id')->on('test_questions')->onDelete('cascade');

            // Indexes for faster queries
            $table->index(['test_id', 'student_id', 'session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
