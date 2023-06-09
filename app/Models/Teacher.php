<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'dob',
        'qualification',
        'class_id',
        'teaching_subject_id',
    ];

    protected $hidden = [
        'id'
    ];
}
