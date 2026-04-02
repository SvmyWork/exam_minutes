<?php

namespace App\Http\Controllers;

use App\Models\StudentLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Test;
use App\Models\Question;
use App\Models\TestMetadata;
use Illuminate\Support\Facades\Cache;

class StudentController extends Controller
{
    private function ConvertIdToString($id)
    {
        $id = (int) $id;
        // if id starts with 10 then replace 10 with TS-
        if (Str::startsWith($id, '10')) {
            return Str::replaceFirst('10', 'TC-', $id);
        }else if (Str::startsWith($id, '20')) {
            return Str::replaceFirst('20', 'ST-', $id);
        }else if (Str::startsWith($id, '30')) {
            return Str::replaceFirst('30', 'TS-', $id);
        }else if (Str::startsWith($id, '40')) {
            return Str::replaceFirst('40', 'T-', $id);
        }else if (Str::startsWith($id, '50')) {
            return Str::replaceFirst('50', 'q', $id);
        }else if (Str::startsWith($id, '60')) {
            return Str::replaceFirst('60', 'sec', $id);
        }
        return (string) $id;
    }
    private function ConvertIdToInteger($code)
    {   
        // Ensure it's a string
        $code = (string) $code;

        if (Str::startsWith($code, 'TC-')) {
            return (int) Str::replaceFirst('TC-', '10', $code);
        } else if (Str::startsWith($code, 'ST-')) {
            return (int) Str::replaceFirst('ST-', '20', $code);
        } else if (Str::startsWith($code, 'TS-')) {
            return (int) Str::replaceFirst('TS-', '30', $code);
        } else if (Str::startsWith($code, 'T-')) {
            return (int) Str::replaceFirst('T-', '40', $code);
        } else if (Str::startsWith($code, 'q')) {
            return (int) Str::replaceFirst('q', '50', $code);
        } else if (Str::startsWith($code, 'section')) {
            return (int) Str::replaceFirst('section', '60', $code);
        }

        // If no known prefix, return as integer directly
        return (int) $code;
    }

    public function student_login(Request $request)
    {   
        $title = 'Student Portal';
        $user_type = 'student';
        $message = $request->session()->get('message');

        return view('common.login', ['title' => $title, 'user_type' => $user_type, 'message' => $message]);
    }

    public function student_register(Request $request)
    {
        $title = 'Student Portal';
        $user_type = 'student';
        $message = $request->session()->get('message'); // or session('message');
        
        return view('common.register', ['title' => $title,  'user_type' => $user_type, 'message' => $message]);
    }

    public function student_forgot()
    {
        $title = 'Student Portal';
        $user_type = 'student';

        return view('common.forgot', ['title' => $title, 'user_type' => $user_type]);
    }

    // ## added by svmy
    // public function registerStudent(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:100',
    //         'email' => 'required|email|unique:student_logins,email',
    //         'password' => 'required|string|min:6|confirmed',
    //     ]);

    //     StudentLogin::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     return redirect()->route('student.login')->with('success', 'Registration successful. Please login.');
    // }

    // ## added by svmy
    // public function authenticateStudent(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $student = StudentLogin::where('email', $request->email)->first();

    //     if ($student && Hash::check($request->password, $student->password)) {
    //         session(['student_id' => $student->id]);
    //         return redirect()->route('common.student_home');
    //     }

    //     return back()->withErrors(['Invalid credentials']);
    // }

    // ## added by svmy
    // public function logoutStudent()
    // {
    //     session()->forget('student_id');
    //     return redirect()->route('student.login');
    // }

    public function upload_image(Request $request)
    {
        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);

                return response()->json(['status' => 'success', 'filename' => $filename]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'No image found'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error'], 500);
        }
    }

    // public function manage_question(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'question_no' => 'required|string|max:255',
    //         ]);

    //         $question_no = (int) $request->input('question_no');

    //         $questions = [
    //             1 => 'What is the capital of France?',
    //             2 => 'What is the largest planet in our solar system?',
    //             3 => 'What is the chemical symbol for water?',
    //             4 => 'Who wrote "To Kill a Mockingbird"?',
    //             5 => 'What is the powerhouse of the cell?',
    //             6 => 'What is the speed of light?',
    //             7 => 'What is the main language spoken in Brazil?',
    //         ];

    //         if (!isset($questions[$question_no])) {
    //             return response()->json(['status' => 'error', 'message' => 'Invalid question number'], 400);
    //         }

    //         Log::info('Question managed', ['question_no' => $question_no]);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Question managed successfully',
    //             'question' => $questions[$question_no],
    //             'options' => ["Epidermis", "Parenchyma", "Phloem", "Xylem"],
    //         ]);
    //     } catch (Exception $e) {
    //         Log::error('Manage question error: ' . $e->getMessage());
    //         return response()->json(['status' => 'error', 'message' => 'Internal server error'], 500);
    //     }
    // }

    public function testSeriesView()
    {   
        $testSeriesName = 'Math Series';
        $creationDate = '29/05/2025 at 1:35:15 AM';
        $tests = [
            (object)[
                'name' => 'Math 1',
                'tests_count' => 5,
                'modifyDate' => '29/05/2025 at 1:35:15 AM'
            ],
            (object)[
                'name' => 'Math 2',
                'tests_count' => 8,
                'modifyDate' => '29/05/2025 at 1:35:15 AM'
            ],
            (object)[
                'name' => 'Math 3',
                'tests_count' => 3,
                'modifyDate' => '29/05/2025 at 1:35:15 AM'
            ],
            (object)[
                'name' => 'Math 4',
                'tests_count' => 4,
                'modifyDate' => '29/05/2025 at 1:35:15 AM'
            ],
            (object)[
                'name' => 'Math 5',
                'tests_count' => 6,
                'modifyDate' => '29/05/2025 at 1:35:15 AM'
            ],
        ];
        return view('teacher.test-series', ['testSeriesName' => $testSeriesName, 'creationDate'=>$creationDate,'tests' => $tests]);
    }

    public function testDetailsView()
    {   
        $testSeriesName = 'Math Series';
        $testName = 'Math 1';
        $creationDate = '29/05/2025 at 1:35:15 AM';
        $tests = [
            (object)[
                'name' => 'Math 1',
                'tests_count' => 5,
                'modifyDate' => '29/05/2025 at 1:35:15 AM'
            ],
            (object)[
                'name' => 'Math 2',
                'tests_count' => 8,
                'modifyDate' => '29/05/2025 at 1:35:15 AM'
            ],
            (object)[
                'name' => 'Math 3',
                'tests_count' => 3,
                'modifyDate' => '29/05/2025 at 1:35:15 AM'
            ],
            (object)[
                'name' => 'Math 4',
                'tests_count' => 4,
                'modifyDate' => '29/05/2025 at 1:35:15 AM'
            ],
            (object)[
                'name' => 'Math 5',
                'tests_count' => 6,
                'modifyDate' => '29/05/2025 at 1:35:15 AM'
            ],
        ];
        return view('teacher.test-details', ['testSeriesName' => $testSeriesName, 'testName' => $testName, 'creationDate'=>$creationDate,'tests' => $tests]);
    }

    public function student_home(Request $request){
        $studentId = session('student_id');
        $studentName = session('student_name');
        $allTests = Test::get(['id', 'test_id', 'test_name']);

        $request->session()->put('student_id', $studentId);
        $request->session()->put('student_name', $studentName);

        return view('student.dashboard', ['name' => $studentName, 'student_id' => $studentId, 'tests' => $allTests]);
    }

    public function student_exam(Request $request)
    {
        if ($request->query('test_id')) {
            $testId = $request->query('test_id');
            $test = Test::where('test_id', $testId)->first();

            if ($test) {
                $request->session()->put('test_id', $testId);
                $teacherId = $test->teacher_id;
                $testSeriesId = $test->test_series_id;

                $allQuestions = Question::where('teacher_id', $teacherId)
                    ->where('test_series_id', $testSeriesId)
                    ->where('test_id', $testId)
                    ->where('is_removed', false)
                    ->get();
                $metadata = TestMetadata::where('Testid', $testId)->first();
                Log::info('Test metadata', ['metadata' => $metadata]);
                $data = [
                    'PaperName' => $metadata->test_name,
                    'PaperId' => $this->ConvertIdToString($testId),
                    'TeacherName' => $metadata->teacher_name,
                    'TeacherId' => $this->ConvertIdToString($metadata->teacher_id),
                    'TotalQuestion' => $metadata->TotalQuestion,
                    'TotalSection' => $metadata->TotalSection,
                    'SectionName' => $metadata->SectionName,
                    'SectionTotalQuestion' => [4],
                    'SectionInitialQuestion' => [1],
                    'CurrentSectionName' => $metadata->SectionName[0],
                    'CurrentSectionTotalQuestion' => 4,
                    'SectionWiseTime' => $metadata->SectionWiseTime == 1 ? true : false,
                    'SectionWiseTotalTime' => $metadata->SectionWiseTotalTime,
                    'TotalTime' => $metadata->TotalTime,
                    'Calculator' => $metadata->Calculator == 1 ? true : false,
                ];
                $Timeinit = "05:00";
                
                return view('teacher.exam.login', ['test' => $test, 'questions' => $allQuestions, 'data' => $data, 'Timeinit' => $Timeinit, 'test_id' => $testId]);


            } else {
                return redirect()->route('student.home')->with('message', 'Test not found.');
            }
        } else {
            return redirect()->route('student.home')->with('message', 'Test ID is required.');
        }
    }

}
