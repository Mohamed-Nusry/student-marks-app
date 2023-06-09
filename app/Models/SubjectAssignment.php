<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'title',
        'deadline',
        'assignment_file',
    ];

    protected $hidden = [
        'subject_id',
    ];

    public function subject() {
        return $this->belongsTo('App\Models\Subject', 'subject_id', 'id');
    }


}
