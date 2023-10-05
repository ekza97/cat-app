<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherHasMapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'mapel_id'
    ];
}