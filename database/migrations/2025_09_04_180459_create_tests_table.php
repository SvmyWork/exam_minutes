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
        Schema::create('tests', function (Blueprint $table) {
            $table->id('id'); // Primary key
            $table->unsignedBigInteger('teacher_user_id'); // FK referencing teacher/user
            $table->unsignedBigInteger('test_series_id'); // FK referencing test series
            $table->unsignedBigInteger('test_id')->unique(); // unique test identifier
            $table->string('test_series_name')->nullable();
            $table->string('test_name');
            $table->integer('num_questions')->default(0);
            $table->integer('full_marks')->default(0);
            $table->integer('duration_minutes')->default(0);
            $table->enum('test_level', ['easy', 'medium', 'hard'])->default('medium');
            $table->string('subject')->nullable();
            $table->json('test_metadata')->nullable(); // Flexible for negative marks, instructions, etc.
            $table->timestamps();

            // Foreign key (optional, depends on your users/teachers table)
            // $table->foreign('teacher_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
