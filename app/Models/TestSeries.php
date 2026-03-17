<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TestSeries extends Model
{
    use HasFactory;

    protected $table = 'test_series'; // table name

    protected $fillable = [
        'name',
        'teacher_id',
        'test_series_id',
        'no_of_tests',
    ];
    
}
