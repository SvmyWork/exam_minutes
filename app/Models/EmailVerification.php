<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailVerification extends Model
{
    use HasFactory;

    protected $table = 'email_verifications';

    protected $fillable = [
        'email',
        'user_type',
        'otp_code',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];
}
