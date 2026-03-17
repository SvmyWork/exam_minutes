<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestMetadata extends Model
{
    protected $table = 'tests_metadata';

    protected $fillable = [
        'Testid',
        'test_name',
        'test_series_id',
        'teacher_name',
        'teacher_id',
        'TotalQuestion',
        'TotalSection',
        'SectionName',
        'SectionwiseTotalQuestion',
        'SectionInitialQuestionid',
        'SectionWiseTime',
        'SectionWiseTotalTime',
        'TotalTime',
        'Calculator',
        'exam_start_date',
        'exam_end_date',
        'status'
    ];

    protected $casts = [
        'SectionName' => 'array',
        'SectionwiseTotalQuestion' => 'array',
        'SectionInitialQuestionid' => 'array',
        'SectionWiseTotalTime' => 'array',
    ];
}
