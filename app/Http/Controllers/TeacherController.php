<?php

namespace App\Http\Controllers;

use App\Models\StudentLogin;
use App\Models\TeacherLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestSeries;
use Illuminate\Support\Facades\Cache;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
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
        }

        // If no known prefix, return as integer directly
        return (int) $code;
    }


    public function teacher_login(Request $request)
    {   
        $title = 'Teacher Portal';
        $user_type = 'teacher';
        $message = $request->session()->get('message');

        return view('common.login', ['title' => $title, 'user_type' => $user_type, 'message' => $message]);
    }

    public function teacher_register(Request $request)
    {
        $title = 'Teacher Portal';
        $user_type = 'teacher';
        $message = $request->session()->get('message'); // or session('message');

        return view('common.register', [
            'title' => $title,
            'user_type' => $user_type,
            'message' => $message,
        ]);
    }


    public function teacher_forgot()
    {
        $title = 'Teacher Portal';
        $user_type = 'teacher';

        return view('common.forgot', ['title' => $title, 'user_type' => $user_type]);
    }
    public function start_up()
    {
        // $testSeries = [
        //     (object)[
        //         'name' => 'Math Series',
        //         'tests_count' => 5,
        //         'modifyDate' => '29/05/2025 at 1:35:15 AM'
        //     ],
        //     (object)[
        //         'name' => 'Science Series',
        //         'tests_count' => 8,
        //         'modifyDate' => '29/05/2025 at 1:35:15 AM'
        //     ],
        //     (object)[
        //         'name' => 'History Series',
        //         'tests_count' => 3,
        //         'modifyDate' => '29/05/2025 at 1:35:15 AM'
        //     ],
        //     (object)[
        //         'name' => 'Geography Series',
        //         'tests_count' => 4,
        //         'modifyDate' => '29/05/2025 at 1:35:15 AM'
        //     ],
        //     (object)[
        //         'name' => 'English Series',
        //         'tests_count' => 6,
        //         'modifyDate' => '29/05/2025 at 1:35:15 AM'
        //     ],
        // ];
        $email_id = Cache::get('teacher_email');
        $teacher_id = Cache::get('teacher_id');
        // convert to string
        $teacher_id = (string) $teacher_id;
        // dd($email_id, $user_id);
        // If teacher_id not found in cache
        if (empty($email_id)) {
            return redirect()
                ->route('teacher.login')
                ->with('message', 'Session expired. Please log in again.');
        }

        $tokenKey = 'teacher_token_' . $email_id;

        if (!Cache::has($tokenKey)) {
            return redirect()
                ->route('teacher.login')
                ->with('message', 'Token missing. Please log in again.');
        }

        $token = Cache::get($tokenKey);
        // dd($token, $email_id, $teacher_id);
        return view('teacher.landing',[ 'token' => $token, 'email_id' => $email_id, 'teacher_id' => $teacher_id]);
    }

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

    public function manage_question(Request $request)
    {
        try {
            $request->validate([
                'question_no' => 'required|string|max:255',
            ]);

            $question_no = (int) $request->input('question_no');

            $questions = [
                1 => 'What is the capital of France?',
                2 => 'What is the largest planet in our solar system?',
                3 => 'What is the chemical symbol for water?',
                4 => 'Who wrote "To Kill a Mockingbird"?',
                5 => 'What is the powerhouse of the cell?',
                6 => 'What is the speed of light?',
                7 => 'What is the main language spoken in Brazil?',
            ];

            if (!isset($questions[$question_no])) {
                return response()->json(['status' => 'error', 'message' => 'Invalid question number'], 400);
            }

            Log::info('Question managed', ['question_no' => $question_no]);

            return response()->json([
                'status' => 'success',
                'message' => 'Question managed successfully',
                'question' => $questions[$question_no],
                'options' => ["Epidermis", "Parenchyma", "Phloem", "Xylem"],
            ]);
        } catch (Exception $e) {
            Log::error('Manage question error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error'], 500);
        }
    }

    public function testSeriesView($id)
    {
        // Check if ID is missing or empty
        if (empty($id)) {
            return redirect()
                ->route('teacher.login')
                ->with('message', 'Test Series ID missing. Please select a test series again.');
        }

        // Convert and find the Test Series
        $testSeriesId = $id;
        $convertedId = $this->ConvertIdToInteger($testSeriesId);
        $data = TestSeries::where('test_series_id', $convertedId)->first();

        // If no data found, redirect to login with message
        if (!$data) {
            return redirect()
                ->route('teacher.login')
                ->with('message', 'Test Series not found or has been deleted.');
        }

        // Prepare data
        $testSeriesName = $data->name;
        $creationDate = $data->created_at->format('d/m/Y \a\t h:i:s A');
        $modifyDate = $data->updated_at->format('d/m/Y \a\t h:i:s A');
        $teacher_id = $this->ConvertIdToString($data->teacher_id);

        // Store session variable
        session(['test_series_id' => $testSeriesId]);

        // Return the view
        return view('teacher.tests', [
            'testSeriesName' => $testSeriesName,
            'creationDate'   => $creationDate,
            'testSeriesId'   => $testSeriesId,
            'teacher_id'     => $teacher_id,
            'modifyDate'     => $modifyDate,
        ]);
    }


    public function testDetailsView($test_id)
    {   
        if (empty($test_id)) {
            return redirect()
                ->route('teacher.login')
                ->with('message', 'Test ID missing. Please select a test again.');
        }

        $data = Test::where('test_id', $this->ConvertIdToInteger($test_id))->first();

        if (!$data){
            return redirect()
                ->route('teacher.login')
                ->with('message', 'Test not found or has been deleted.');
        }

        $test_name = $data->test_name;
        $test_series_name = $data->test_series_name;
        $teacherId = $this->ConvertIdToString($data->teacher_user_id);
        $testSeriesId = $this->ConvertIdToString($data->test_series_id);
        $questionSequence = $data->question_sequence;

        Log::info(['test_id' => $test_id, 
                                'test_name' => $test_name, 
                                'test_series_name' => $test_series_name,
                                'teacher_id' => $teacherId,
                                'test_series_id' => $testSeriesId,
                                'question_sequence' => $questionSequence]);
        return view('teacher.test-editor', ['test_id' => $test_id, 
                                'test_name' => $test_name, 
                                'test_series_name' => $test_series_name,
                                'teacher_id' => $teacherId,
                                'test_series_id' => $testSeriesId,
                                'question_sequence' => $questionSequence]);
    }
    
}


