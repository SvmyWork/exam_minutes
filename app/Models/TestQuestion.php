<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TestQuestion extends Model
{
    use HasFactory;

    // Table name (optional if Laravel can guess it)
    protected $table = 'test_questions';

    // Primary key (default is "id")
    protected $primaryKey = 'id';

    // Fillable fields for mass assignment
    protected $fillable = [
        'teacher_id',
        'test_series_id',
        'test_series_name',
        'test_id',
        'test_name',
        'section_id',
        'section_name',
        'question_id',
        'question_text',
        'question_type',
        'options',
        'correct_answer',
        'marks',
        'negative_marks',
        'difficulty_level',
        'explanation',
    ];

    // Casts for JSON & other types
    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'array',
        'marks' => 'decimal:2',
        'negative_marks' => 'decimal:2',
    ];

    
}
