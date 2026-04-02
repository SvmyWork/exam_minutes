<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function show_instructions(Request $request)
    {
        $testId = $request->query('test_id');
        Log::info("Showing instructions for test_id: $testId");
        $test = Test::where('test_id', $testId)->first();
        $test_name = $test ? $test->test_name : 'Unknown Test';
        Log::info("Test name: $test_name");
        $request->session()->put('test_name', $test_name);
        $request->session()->put('test_id', $testId);
        return view('teacher.exam.instructions', ['test_id' => $testId, 'test_name' => $test_name]);
    }

    public function start_exam($paperid)
    {
        // Here you would typically fetch the exam details based on the paper ID
        // For now, we will just return a view with the paper ID
        $total_questions = 10; // Example total questions, replace with actual logic
        $student_id = session('student_id'); // Assuming student ID is stored in session
        $student_name = session('student_name'); // Assuming student name is stored in session
        $test_id = session('test_id'); // Assuming test ID is stored in session
        $test_name = session('test_name'); // Assuming test name is stored in session
        Log::info("Starting exam for paper ID: $paperid, student ID: $student_id, test ID: $test_id");
        return view('teacher.exam.index', ['paperid' => $paperid, 'total_questions' => $total_questions, 'student_id' => $student_id, 'student_name' => $student_name, 'test_id' => $test_id, 'test_name' => $test_name]);
    }

    public function exit_exam() {
        return view('teacher.exam.endExam');
    }
}
