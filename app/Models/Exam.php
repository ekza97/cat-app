<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'mapel_id',
        'name',
        'jml_soal',
        'jml_waktu',
        'type',
        'exam_start',
        'exam_end',
        'token',
        'created_by'
    ];

    /**
     * Get the mapel that owns the Soal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    /**
     * Get the teachers that owns the Soal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teachers()
    {
        return $this->belongsTo(Teacher::class,'teacher_id','id');
    }
}