<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function start_exam($paperid)
    {
        // Here you would typically fetch the exam details based on the paper ID
        // For now, we will just return a view with the paper ID
        $total_questions = 10; // Example total questions, replace with actual logic
        return view('teacher.exam.index', ['paperid' => $paperid, 'total_questions' => $total_questions]);
    }

    public function exit_exam() {
        return view('teacher.exam.endExam');
    }
}
