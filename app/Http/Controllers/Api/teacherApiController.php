<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TestSeries;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class teacherApiController extends Controller
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
    // fetch test series
    public function getTestSeries(Request $request)
    {
        Log::info('Fetching Test Series request received', [
            'payload' => $request->all()
        ]);
        $teacherId = $this->ConvertIdToInteger($request->input('teacher_id'));
        $data = TestSeries::where('teacher_id', $teacherId)
                ->orderBy('id', 'desc')
                ->get();
        Log::info('Raw Test Series data fetched', [
            'data_count' => $data->count()
        ]);
        // Transform each item into array with formatted updated_at
        $formattedData = $data->map(function ($item) {
            return [
                'id'          => $item->id,
                'name'        => $item->name,
                'teacher_id'  => $this->ConvertIdToString($item->teacher_id),
                'test_series_id' => $this->ConvertIdToString($item->test_series_id),
                'no_of_tests' => $item->no_of_tests,
                'created_at'  => $item->created_at->format('d/m/Y \a\t h:i:s A'),
                'updated_at'  => $item->updated_at->format('d/m/Y \a\t h:i:s A'),
            ];
        });

        Log::info('Test Series fetched successfully', [
            'count' => $formattedData->count()
        ]);

        return response()->json($formattedData, 200);
    }

    /**
     * Generate a unique test_series_id (e.g., TS-20251109-ABC123)
     */
    private function generateTestSeriesId(): string
    {
        do {
            $uniqueId = (int) ('0030' . now()->format('YmdHisv'));
        } while (TestSeries::where('test_series_id', $uniqueId)->exists());

        return $uniqueId;
    }


    // create test series
    public function createTestSeries(Request $request)
    {
        Log::info('Creating Test Series request received', [
            'payload' => $request->all()
        ]);

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'teacher_id'  => 'required|string',   // adjust if you use teacher_user_id instead
            'no_of_tests' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed for creating Test Series', [
                'errors' => $validator->errors()
            ]);
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Generate unique test_series_id
        $uniqueSeriesId = $this->generateTestSeriesId();

        // Create Test Series
        $testSeries = TestSeries::create([
            'test_series_id' => $this->ConvertIdToInteger($uniqueSeriesId),
            'name'           => $request->input('name'),
            'teacher_id'     => $this->ConvertIdToInteger($request->input('teacher_id')),
            'no_of_tests'    => $request->input('no_of_tests'),
        ]);

        return response()->json([
            'message' => 'Test series created successfully',
            'data'    => $testSeries
        ], 201);
    }

    private function generateTestId(): string
    {
        do {
            $uniqueId = (int) ('0040' . now()->format('YmdHisv'));
        } while (Test::where('test_id', $uniqueId)->exists());

        return $uniqueId;
    }

    // create test
    public function createTest(Request $request)
    {
        Log::info('Creating Test request received', [
            'payload' => $request->all()
        ]);
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string',
            'test_series_id' => 'required|String',
            'teacher_id'=> 'required|String',
            'test_series_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed for creating Test', [
                'errors' => $validator->errors()
            ]);
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }
        $uniqueTestId = $this->generateTestId();

        // Create Test
        $test =Test::create([
            'teacher_user_id' => $this->ConvertIdToInteger($request->input('teacher_id')),
            'test_series_id' => $this->ConvertIdToInteger($request->input('test_series_id')),
            'test_id' => $this->ConvertIdToInteger($uniqueTestId),
            'test_series_name' => $request->input('test_series_name'),
            'test_name' => $request->input('name'),
        ]);
        // Here you would typically create the Test model and save it to the database.
        // For demonstration, we'll just return the validated data.

        return response()->json([
            'message' => 'Test created successfully',
            'data'    => $test
        ], 201);
    }

    // get tests
    public function getTests(Request $request)
    {
        Log::info('Fetching Tests request received', [
            'payload' => $request->all()
        ]);

        // For demonstration, returning a static list of tests.
        $tests = Test::where('test_series_id', $this->ConvertIdToInteger($request->input('test_series_id')))
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id'               => $item->id,
                        'test_id'         => $this->ConvertIdToString($item->test_id),
                        'test_series_id'  => $this->ConvertIdToString($item->test_series_id),
                        'test_series_name'=> $item->test_series_name,
                        'test_name'       => $item->test_name,
                        'num_questions'   => $item->num_questions,
                        'full_marks'      => $item->full_marks,
                        'duration_minutes'=> $item->duration_minutes,
                        'test_level'     => $item->test_level,
                        'subject'        => $item->subject,
                        'test_metadata'  => $item->test_metadata,
                        'created_at'     => $item->created_at->format('d/m/Y \a\t h:i:s A'),
                        'updated_at'     => $item->updated_at->format('d/m/Y \a\t h:i:s A'),
                    ];
                });

        $testSeries = TestSeries::where('test_series_id', $this->ConvertIdToInteger($request->input('test_series_id')))->first();

        if ($testSeries) {
            $testSeries->no_of_tests = count($tests); // ✅ assign count
            $testSeries->save();                     // ✅ save changes
        }


        Log::info('Tests fetched successfully', [
            'count' => count($tests)
        ]);

        return response()->json($tests, 200);
    }

    public function saveQuestion(Request $request)
    {
        Log::info('all data', [
            'payload' => $request->all()
        ]);
        // Validate request
        $validated = $request->validate([
            'teacherId'     => 'required|string',
            'testSeriesId'  => 'required|string',
            'testId'        => 'required|string',
            'questionId'    => 'required|string',
            'position'      => 'sometimes|integer',
            'positionIndex' => 'sometimes|integer',
            'type'          => 'required|string',
            'title'         => 'required|string',
            'description'   => 'nullable|string',
            'options'       => 'nullable|array|nullable',
            'imageUrl'      => 'nullable|string',
            'answer'        => 'nullable',
            'note'          => 'nullable|string',
        ]);

        Log::info('Validated data', [
            'validated_payload' => $validated
        ]);
        // Convert IDs
        $teacher_id     = $this->ConvertIdToInteger($validated['teacherId']);
        $test_series_id = $this->ConvertIdToInteger($validated['testSeriesId']);
        $test_id        = $this->ConvertIdToInteger($validated['testId']);
        $question_id    = $this->ConvertIdToInteger($validated['questionId']);
        $newPosition    = isset($validated['position']) ? (int)$validated['position'] : (isset($validated['positionIndex']) ? (int)$validated['positionIndex'] : 0);
        $validated['options'] = $validated['options'] ?? [];
        // Encode options
        $encodedOptions = json_encode($validated['options']);
        
        // Handle answer if it is an array (for multiple choice/checkbox)
        if (isset($validated['answer']) && is_array($validated['answer'])) {
            $validated['answer'] = json_encode($validated['answer']);
        }

        // Find existing question
        $existing = Question::where('question_id', $question_id)
            ->where('test_id', $test_id)
            ->first();

        // Create new question if not found
        if (!$existing) {
            $question = Question::create([
                'position'        => $newPosition,
                'teacher_id'      => $teacher_id,
                'test_series_id'  => $test_series_id,
                'test_id'         => $test_id,
                'question_id'     => $question_id,
                'questionType'    => $validated['type'],
                'questionTitle'   => $validated['title'],
                'description'     => $validated['description'],
                'options'         => $encodedOptions,
                'imageUrl'        => $validated['imageUrl'],
                'answer'          => $validated['answer'] ?? null,
                'note'            => $validated['note'] ?? null,
                'is_removed'      => 0,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Question created successfully',
                'data'    => $question,
            ]);
        }

        // Compare for changes
        $existingPosition = (int) $existing->position;

        $hasChanges =
            $existingPosition        !== $newPosition ||
            $existing->questionType  !== $validated['type'] ||
            $existing->questionTitle !== $validated['title'] ||
            $existing->description   !== $validated['description'] ||
            $existing->options       !== $encodedOptions ||
            $existing->imageUrl      !== $validated['imageUrl'] ||
            $existing->answer        !== ($validated['answer'] ?? null) ||
            $existing->note          !== ($validated['note'] ?? null);

        // Only update if something changed
        if ($hasChanges) {
            // ✅ If position changed, swap with the other question
            if ($existingPosition !== $newPosition) {
                $prevPositionQ = Question::where('test_id', $test_id)
                    ->where('position', $newPosition)
                    ->where('question_id', '!=', $question_id)
                    ->first();

                if ($prevPositionQ) {
                    $prevPositionQ->update(['position' => $existingPosition]);
                }
            }

            // Now update the current question
            $existing->update([
                'position'       => $newPosition,
                'questionType'   => $validated['type'],
                'questionTitle'  => $validated['title'],
                'description'    => $validated['description'],
                'options'        => $encodedOptions,
                'imageUrl'       => $validated['imageUrl'],
                'answer'         => $validated['answer'] ?? null,
                'note'           => $validated['note'] ?? null,
                'is_removed'     => 0,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Question updated successfully',
                'data'    => $existing,
            ]);
        }
        // update total no of questions in test
        $totalQuestions = Question::where('test_id', $test_id)
            ->where('is_removed', false)
            ->count();

        Test::where('test_id', $test_id)->update(['num_questions' => $totalQuestions]);

        // No change
        return response()->json([
            'status'  => 'no_change',
            'message' => 'No changes detected — question not updated',
            'data'    => $existing,
        ]);
    }

    public function deleteQuestion(Request $request)
    {
        // ✅ Validate input
        Log::info('all delete data', [
            'payload' => $request->all()
        ]);
        $validated = $request->validate([
            'teacherId'  => 'required|string',
            'testId'     => 'required|string',
            'questionId' => 'required|string',
        ]);
        // ✅ Convert IDs to integer form
        $teacher_id  = $this->ConvertIdToInteger($validated['teacherId']);
        $test_id     = $this->ConvertIdToInteger($validated['testId']);
        $question_id = $this->ConvertIdToInteger($validated['questionId']);
        // ✅ Find the question
        $question = Question::where('teacher_id', $teacher_id)
            ->where('test_id', $test_id)
            ->where('question_id', $question_id)
            ->first();

        if (!$question) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Question not found',
            ]);
        }

        // ✅ Mark as removed   
        $question->update(['is_removed' => true]);

        // ✅ Update total no of questions in test
        $totalQuestions = Question::where('test_id', $test_id)
            ->where('is_removed', false)
            ->count();
        Test::where('test_id', $test_id)->update(['num_questions' => $totalQuestions]);
        // ✅ Return success response
        return response()->json([
            'status'  => 'success',
            'message' => 'Question deleted successfully',
        ]);
    }

    public function getQuestions(Request $request)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'teacherId' => 'required|string',
            'testId'    => 'required|string',
        ]);

        Log::info('Fetching Questions request received', [
            'payload' => $validated
        ]);

        // ✅ Convert IDs to integer form
        $teacher_id = $this->ConvertIdToInteger($validated['teacherId']);
        $test_id    = $this->ConvertIdToInteger($validated['testId']);

        // ✅ Fetch all active (not removed) questions for this teacher + test
        $questions = Question::where('teacher_id', $teacher_id)
            ->where('test_id', $test_id)
            ->where('is_removed', false)
            ->orderBy('position', 'asc')
            ->get();

        // ✅ Decode JSON options for easier frontend handling
        $questions->transform(function ($question) {
            $question->options = json_decode($question->options, true);
            return $question;
        });

        $test = Test::where('teacher_user_id', $teacher_id)
            ->where('test_id', $test_id)
            ->first();

        $sequence = $test ? $test->question_sequence : [];

        // ✅ Return success response
        return response()->json([
            'status'  => 'success',
            'count'   => $questions->count(),
            'data'    => $questions,
            'sequence'=> $sequence,
        ]);
    }

    public function updateSequence(Request $request)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'teacherId' => 'required|string',
            'testId'    => 'required|string',
            'sequence'  => 'required|array',
        ]);

        Log::info('Updating Question Sequence request received', [
            'payload' => $validated
        ]);
        $teacher_id = $this->ConvertIdToInteger($validated['teacherId']);
        $test_id    = $this->ConvertIdToInteger($validated['testId']);

        $test = Test::where('teacher_user_id', $teacher_id)
            ->where('test_id', $test_id)
            ->first();

        if (!$test) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Test not found',
            ]);
        }

        for ($i = 0; $i < count($validated['sequence']); $i++) {
            $question_id = $validated['sequence'][$i];
            if (is_string($question_id)) {
                $question_id = $this->ConvertIdToInteger($question_id);
            }
            $validated['sequence'][$i] = $question_id;
        }

        $test->update([
            'question_sequence' => $validated['sequence'],
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Question sequence updated successfully',
        ]);
    }

}
