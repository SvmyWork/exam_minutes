<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests'; // table name

    protected $primaryKey = 'id'; // primary key

    protected $fillable = [
        'teacher_user_id',
        'test_series_id',
        'test_id',
        'test_series_name',
        'test_name',
        'num_questions',
        'full_marks',
        'duration_minutes',
        'test_level',
        'subject',
        'test_metadata',
        'question_sequence',
    ];
    protected $casts = [
        'test_metadata' => 'array',
        'question_sequence' => 'json',
    ];
}
