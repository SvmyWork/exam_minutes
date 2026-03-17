<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'position',
        'teacher_id',
        'test_series_id',
        'test_id',
        'question_id',
        'questionType',
        'questionTitle',
        'description',
        'options',
        'imageUrl',
        'answer',
        'note',
        'is_removed',
    ];

    protected $casts = [
        'teacher_id'     => 'integer',
        'test_series_id' => 'integer',
        'position'       => 'integer',
        'test_id'        => 'integer',
        'options'        => 'array', // auto JSON encode/decode
        'is_removed'     => 'boolean',
    ];
}
