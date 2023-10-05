<?php

namespace App\Models;

use App\Models\Mapel;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Soal extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'mapel_id',
        'bobot',
        'file',
        'file_type',
        'soal',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'opsi_e',
        'jawaban',
        'soal_type'
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