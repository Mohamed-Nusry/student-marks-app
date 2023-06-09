<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function slides() {
        return $this->hasMany('App\Models\LectureSlide', 'subject_id', 'id');
    }

    public function assignments() {
        return $this->hasMany('App\Models\SubjectAssignment', 'subject_id', 'id');
    }
}
