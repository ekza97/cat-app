<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mapels')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->integer('jml_soal');
            $table->integer('jml_waktu');
            $table->enum('type',['acak','urut']);
            $table->dateTime('exam_start');
            $table->dateTime('exam_end');
            $table->string('token');
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};