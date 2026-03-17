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
        Schema::create('test_series', function (Blueprint $table) {
            $table->id('id'); // testseries id (primary key)
            $table->string('name'); // test series name
            $table->unsignedBigInteger('teacher_id'); // teacher id (foreign key if needed)
            $table->unsignedBigInteger('test_series_id')->unique(); // unique test series identifier
            $table->integer('no_of_tests')->default(0); // number of tests
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_series');
    }
};
