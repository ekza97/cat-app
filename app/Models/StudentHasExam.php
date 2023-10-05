<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentHasExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'user_id',
        'list_soal',
        'list_jawaban',
        'jml_benar',
        'nilai',
        'nilai_bobot',
        'exam_start',
        'exam_end',
        'status'
    ];

    /**
     * Get the exams that owns the StudentHasExam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exams()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the users that owns the StudentHasExam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}