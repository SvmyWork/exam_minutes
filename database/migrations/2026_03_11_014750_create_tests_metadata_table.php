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
        Schema::create('tests_metadata', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Testid');

            $table->integer('TotalSection');

            $table->json('SectionName')->nullable();

            $table->json('SectionwiseTotalQuestion')->nullable();

            $table->json('SectionInitialQuestionid')->nullable();

            $table->boolean('SectionWiseTime')->default(false);

            $table->json('SectionWiseTotalTime')->nullable();

            $table->integer('TotalTime');

            $table->boolean('Calculator')->default(false);

            // Exam schedule
            $table->dateTime('exam_start_date');
            $table->dateTime('exam_end_date');

            // Status
            $table->integer('status')->default(0); 
            // example: 0 - draft, 1 - active, 2 - completed, 3 - inactive, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests_metadata');
    }
};
