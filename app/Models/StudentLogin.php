<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentLogin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['email', 'password', 'name', 'student_id', 'is_banned', 'remember_token'];
}
