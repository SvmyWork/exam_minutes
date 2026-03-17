<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ Correct class
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherLogin extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $table = 'teacher_logins';

    protected $fillable = ['name', 'email', 'password', 'subject', 'teacher_id', 'is_banned'];

    protected $hidden = ['password', 'remember_token'];
}
