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
        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('student_name')->nullable(); // optional redundancy
            $table->string('class')->nullable();
            $table->unsignedBigInteger('enrolled_test_id');
            $table->unsignedBigInteger('enrolled_series_id');
            $table->boolean('access_flag')->default(0); // 0 = not allowed, 1 = allowed
            $table->integer('attempt_count')->default(0);
            $table->timestamps();

            // Foreign keys (if related tables exist)
            // $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            // $table->foreign('enrolled_test_id')->references('id')->on('tests')->onDelete('cascade');
            // $table->foreign('enrolled_series_id')->references('id')->on('test_series')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_enrollments');
    }
};
