<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedule_entries';

    protected $fillable = [
    'student_id',
    'course_id',
    'section_id',
    'day',
    'start_time',
    'end_time',
    'room'
    ];


            public function course()
        {
            return $this->belongsTo(\App\Models\Course::class);
        }

        public function section()
        {
            return $this->belongsTo(\App\Models\Section::class);
        }
}
