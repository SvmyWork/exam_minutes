<?php

namespace App\Http\Controllers;

use App\Mail\verifyEmail;
use Illuminate\Support\Str;
use App\Models\StudentLogin;
use App\Models\TeacherLogin;
use Illuminate\Http\Request;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Exception;

class CommonController extends Controller
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

    private function generateStudentId(): string
    {
        do {
            $uniqueId = (int) ('0020' . now()->format('YmdHisv'));
        } while (StudentLogin::where('student_id', $uniqueId)->exists());

        return $uniqueId;
    }
    private function generateTeacherId(): string
    {
        do {
            $uniqueId = (int) ('0010' . now()->format('YmdHisv'));
        } while (TeacherLogin::where('teacher_id', $uniqueId)->exists());

        return $uniqueId;
    }


    public function register_store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:student,teacher',
            ]);

            Log::info('Registration attempt', [
                'email' => $validatedData['email'],
                'name' => $validatedData['name'],
                'role' => $validatedData['role'],
            ]);

            if ($validatedData['role'] === 'student') {
                $StudentId = $this->generateStudentId();
                StudentLogin::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'student_id' => $StudentId,
                    'remember_token' => null, // Assuming you don't need this field
                ]);
                
                return redirect()->route('common.emailverify', [
                    'message'   => 'Registration successful. Please login.',
                    'email'     => $request->email,
                    'user_type' => 'student',
                    'page_code' => 'L'
                ]);
                
            } elseif ($validatedData['role'] === 'teacher') {
                $TeacherId = $this->generateTeacherId();
                TeacherLogin::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'remember_token' => null, // Assuming you don't need this field
                    'subject' => '',
                    'teacher_id' => $TeacherId,
                ]);

                return redirect()->route('common.emailverify', [
                    'message'   => 'Registration successful. Please login.',
                    'email'     => $request->email,
                    'user_type' => 'teacher',
                    'page_code' => 'L'
                ]);
            } 
        } catch (\Exception $e) {
            // Log the error with details
            Log::error('Registration failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            return redirect()->back()->with(['message' => 'Registration failed. Please try again later or use a different email address.', 'status' => 'error']);
        }
    }


    public function authenticate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
                'user_type' => 'required|string|in:student,teacher',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $credentials = $validator->validated();

            $isVerified = EmailVerification::where('email', $credentials['email'])
                            ->where('user_type', $credentials['user_type'])
                            ->value('is_verified');

            if (!$isVerified) {
                return redirect()->route('common.emailverify', [
                    'message'   => 'please verify your email address',
                    'email'     => $credentials['email'],
                    'user_type' => $credentials['user_type'],
                    'page_code' => 'L'
                ]);
            }   

            if ($credentials['user_type'] === 'student') {
                $student = StudentLogin::where('email', $credentials['email'])->first();
                
                if ($student && Hash::check($credentials['password'], $student->password) && $isVerified) {

                    Auth::guard('student')->login($student);

                    session(['student_id' => $student->id]);
                    session(['student_name' => $student->name]);
                    
                    return redirect()->route('student.home')->with([
                        'success' => true,
                        'message' => 'Student authenticated successfully',
                        'data' => [
                            'name' => $student->name,
                        ]
                    ]);
                }

                return redirect()->route('student.login')->with([
                    'success' => false,
                    'message' => 'Invalid credentials '
                ]);
            }

            if ($credentials['user_type'] === 'teacher') {
                $teacher = TeacherLogin::where('email', $credentials['email'])->first();
                // dd($teacher->teacher_id);
                if ($teacher && Hash::check($credentials['password'], $teacher->password) && $isVerified) {
                    $teacher_id_str = $this->ConvertIdToString($teacher->teacher_id);
                    session(['teacher_id' => $teacher_id_str]);

                    Auth::guard('teacher')->login($teacher);
                    $token = $teacher->createToken('auth_token')->plainTextToken;

                    
                    Cache::put('teacher_token_' . $teacher->email, $token);
                    Cache::put('teacher_email', $teacher->email);
                    Cache::put('teacher_id', $teacher_id_str);

                    return redirect()->route('teacher.start_up')->with([
                        'success' => true,
                        'message' => 'Teacher authenticated successfully',
                        'data' => [
                            'name' => $teacher->name,
                        ]
                    ]);
                }

                return redirect()->route('teacher.login')->with([
                    'scuccess' => false,
                    'message' => 'Invalid credentials for teacher.'
                ]);
            }

            Log::error('Invalid user type provided during authentication: ' . $credentials['user_type']);
        } catch (Exception $e) {
            Log::error('Authentication failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            
            return redirect()->back()->with(['message' => 'Authentication failed. Please try again later.', 'status' => 'error']);
        }
    }

    public function emailverify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'user_type' => 'required|string',
            'page_code' => 'required|string',
        ]);

        $email = $request->input('email');
        $user_type = $request->input('user_type');
        $page_code = $request->input('page_code');
        return view('common.emailVerify', [
            'email' => $email,
            'user_type' => $user_type,
            'page_code' => $page_code
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'secret_code' => 'required|string',
            'email' => 'required|email',
            'user_type' => 'required|string',
        ]);

        $secret_code = $request->input('secret_code');
        $secret_code = explode('_', $secret_code);
        $secret_code = $secret_code[1];
        $email = $request->input('email');
        $user_type = $request->input('user_type');
        $title = Str::ucfirst($user_type) . ' Portal';

        $user = EmailVerification::where('email', $email)
            ->where('user_type', $user_type)
            ->where('otp_code', $secret_code)
            ->where('is_verified', true)
            ->first();

        if (!$user) {
            return redirect()->route($user_type . '.login')->with('message', 'Invalid secret code.');
        }
        
        return view('common.update_password', [
            'email' => $email,
            'user_type' => $user_type,
            'title' => $title,
        ]);
    }



    public function saveNewPassword(Request $request) {
        $request->validate([
            'confirm_psw' => 'required|string|min:8',
            'email' => 'required|email',
            'user_type' => 'required|string',
        ]);

        $new_psw = $request->confirm_psw;
        $email = $request->email;
        $user_type = $request->user_type;

        Log::info("New Password Request - Email: {$email}, User Type: {$user_type}");

        if ($user_type === 'teacher') {
            $user = TeacherLogin::where('email', $email)->first();
        } elseif ($user_type === 'student') {
            $user = StudentLogin::where('email', $email)->first();
        } else {
            return redirect()->route($user_type . '.login')->with('message', 'Invalid user type');
        }

        if (!$user) {
            return redirect()->route($user_type . '.login')->with('message', 'User not found');
        }

        $user->password = Hash::make($new_psw);
        $user->save();

        Log::info("Password updated successfully for {$email}");

        return redirect()->route($user_type . '.login')->with('message', 'Password updated successfully');
    }

}
