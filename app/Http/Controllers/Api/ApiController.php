<?php

namespace App\Http\Controllers\Api;

use session;
use Exception;
use App\Mail\verifyEmail;
use App\Models\StudentLogin;
use App\Models\TeacherLogin;
use Illuminate\Http\Request;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
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
    

    public function server(Request $request)
    {
        // Get request data
        $data = [
            'timestamp' => now()->toDateTimeString(),
            'data' => $request->all(),
        ];

        // Load existing data or create new array
        $filePath = 'requests/log.json';
        $existing = [];

        if (Storage::exists($filePath)) {
            $json = Storage::get($filePath);
            $existing = json_decode($json, true) ?? [];
        }

        // Append new entry
        $existing[] = $data;

        // Save back to file
        Storage::put($filePath, json_encode($existing, JSON_PRETTY_PRINT));

        // Response
        return response()->json(['message' => 'Request saved and server endpoint reached']);
    }

    public function getExamPaper(Request $request)
    {
        // Sample paper with mixed image/text types
        $testId = $this->ConvertIdToInteger($request->input('testId'));
        $teacherId = $this->ConvertIdToInteger($request->input('teacherId'));
        Log::info('Exam paper request', ['testId' => $testId, 'teacherId' => $teacherId]);
        $paper = Question::where('test_id', $testId)->get();
        Log::info('Exam paper found', ['paper' => $paper->toArray()]);
        $paper = $paper->map(function ($item) {
            return [
                'section' => 'Paper-1',
                'Q' => $item->questionTitle,
                'options' => array_map(function ($item) {
                                return strip_tags($item['text']); // removes HTML like <p>
                            }, json_decode($item->options, true)),
                'qtype' => 'text',
                'anstype' => array_map(function ($item) {
                                return 'text';
                            }, json_decode($item->options, true)),
            ];
        });
        // $paper = [
        //     1 => [
        //         "section" => "Paper-1",
        //         "Q" => "What is the name of the weak zone of the earth’s crust?",
        //         "options" => ["Seismic", "Cosmic", "Formic", "Anaemic"],
        //         "qtype" => "text",
        //         "anstype" => ["text", "text", "text", "text"]
        //     ],
        //     2 => [
        //         "section" => "Paper-1",
        //         "Q" => "What is 2+2?",
        //         "options" => ["3", "4", "5", "6"],
        //         "qtype" => "text",
        //         "anstype" => ["text", "text", "text", "text"]
        //     ],
        //     3 => [
        //         "section" => "Paper-2",
        //         "Q" => "Identify the animal in the image",
        //         "options" => ["lion.jpg", "tiger", "bear", "elephant"],
        //         "qtype" => "text",
        //         "anstype" => ["image", "text", "text", "text"]
        //     ],
        //     4 => [
        //         "section" => "Paper-2",
        //         "Q" => "piechart.svg",
        //         "options" => ["It is a bar graph", "It is a pie chart", "It is a line graph", "It is a histogram"],
        //         "qtype" => "image",
        //         "anstype" => ["text", "text", "text", "text"]
        //     ],
        //     5 => [
        //         "section" => "Paper-2",
        //         "Q" => "Which image shows the Taj Mahal?",
        //         "options" => ["tajmahal", "qutubminar", "gatewayofindia", "redfort"],
        //         "qtype" => "text",
        //         "anstype" => ["text", "text", "text", "text"]
        //     ],
        //     6 => [
        //         "section" => "Paper-3",
        //         "Q" => "Solve: 5 × 3 = ?",
        //         "options" => ["15", "8", "10", "20"],
        //         "qtype" => "text",
        //         "anstype" => ["text", "text", "text", "text"]
        //     ],
        //     7 => [
        //         "section" => "Paper-3",
        //         "Q" => "state_map.png",
        //         "options" => ["Maharashtra", "Tamil Nadu", "Kerala", "Karnataka"],
        //         "qtype" => "image",
        //         "anstype" => ["text", "text", "text", "text"]
        //     ],
        //     8 => [
        //         "section" => "Paper-3",
        //         "Q" => "Which image is the national bird of India?",
        //         "options" => ["peacock", "sparrow", "parrot", "eagle"],
        //         "qtype" => "text",
        //         "anstype" => ["text", "text", "text", "text"]
        //     ],
        //     9 => [
        //         "section" => "Paper-3",
        //         "Q" => "**1 mole** of \$K_4[Fe(CN)_6]\$ contains carbon = **6 g-atoms**.  
        //     **0.5 mole** of \$K_4[Fe(CN)_6]\$ contains carbon = **3 g-atoms**.  
        //     The mass of carbon present in **0.5 mole** of \$K_4[Fe(CN)_6]\$ is:",
        //         "options" => ["**6 g-atoms**. **0.5 mole** of \$K_4[Fe(CN)_6]\$ contains", "18 g", "3.6 g", "1.8 g"],
        //         "qtype" => "markdown",
        //         "anstype" => ["markdown", "text", "text", "text"]
        //     ]


        // ];

        return response()->json([
            'status' => 'success',
            'paper' => $paper
        ]);
    }

    public function sendCode(Request $request)
    {
        // Check if email is present
        if (!$request->has('email') || empty($request->email)) {
            return response()->json([
                'message' => 'Something went wrong. Email is missing.',
                'status' => 'error'
            ], 400);
        }

        // Validate email format
        $request->validate([
            'email' => 'required|email',
            'user_type' => 'required|string',
        ]);

        $email = $request->email;
        $user_type = $request->user_type;

        // chcek email is exists
        if ($user_type == "student"){
            $user = StudentLogin::where('email', $email)->first();
        }
        else if ($user_type == "teacher"){
            $user = TeacherLogin::where('email', $email)->first();
        }
        else{
            return response()->json([
                'message' => 'Invalid user type.',
                'status' => 'error'
            ], 400);
        }
        
        if (!$user) {
            return response()->json([
                'message' => 'Email not found.',
                'status' => 'error'
            ], 404);
        }

        $username = $user->name;
        $subject = "Verify Email";
        $code = $this->generateOtp();

        $emailVerification = EmailVerification::updateOrCreate(
            [
                'email' => $email,
                'user_type' => $user_type
            ],
            [
                'otp_code' => $code,
                'is_verified' => false
            ]
        );

        if ($emailVerification) {
            Log::info('Email verification created or updated successfully');
        } else {
            Log::error('Failed to create or update email verification');
        }

        // Send the verification email
        Mail::to($email)->send(new verifyEmail($subject, $code, $username));

        return response()->json([
            'message' => 'Verification code sent successfully.',
            'status' => 'success'
        ]);
    }

    private function generateOtp()
    {
        //generate 6 digit otp
        $otp = rand(100000, 999999);
        return $otp;
    }

    public function verifyOtp(Request $request)
    {
        // current time
        $current_time = now();

        Log::info('OTP verification attempt started', [
            'email' => $request->email,
            'user_type' => $request->user_type,
            'otp' => $request->otp,
            'time' => $current_time
        ]);

        $request->validate([
            'email' => 'required|email',
            'user_type' => 'required|string',
            'otp' => 'required|digits:6'
        ]);

        $email = $request->email;
        $user_type = $request->user_type;
        $otp = $request->otp;
        
        $user = EmailVerification::where('email', $email)
            ->where('user_type', $user_type)
            ->first();

        if (!$user) {
            Log::warning('OTP verification failed: No verification record found', [
                'email' => $email,
                'user_type' => $user_type
            ]);

            return response()->json([
                'message' => 'No verification record found.',
                'status' => 'error'
            ], 404);
        }

        if ($user->otp_code == $otp) {
            $user->is_verified = true;
            $user->save();

            Log::info('OTP verified successfully', [
                'email' => $email,
                'user_type' => $user_type
            ]);

            return response()->json([
                'message' => 'OTP verified successfully.',
                'status' => 'success'
            ], 200);
        } else {
            Log::error('OTP verification failed: Invalid code', [
                'email' => $email,
                'user_type' => $user_type,
                'provided_otp' => $otp,
                'expected_otp' => $user->otp_code
            ]);

            return response()->json([
                'message' => 'Invalid OTP code.',
                'status' => 'error'
            ], 422);
        }
    }


}
