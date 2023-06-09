<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRank extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'marks',
        'rank',
    ];

    protected $hidden = [
        'student_id',
        'subject_id',
    ];

    public function subject() {
        return $this->belongsTo('App\Models\Subject', 'subject_id', 'id');
    }

    public function student() {
        return $this->belongsTo('App\Models\Student', 'student_id', 'user_id');
    }
}
