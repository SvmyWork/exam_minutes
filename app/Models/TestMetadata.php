<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestMetadata extends Model
{
    protected $table = 'tests_metadata';

    protected $fillable = [
        'Testid',
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
