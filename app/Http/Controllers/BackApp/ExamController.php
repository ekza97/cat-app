<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Exam;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DataTables\ExamDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ExamController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExamDataTable $dataTable)
    {
        return $dataTable->render('backapp.exam.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'mapel_id' => 'required|string|max:191',
                'name' => 'required|string|max:191',
                'jml_soal' => 'required|string|max:191',
                'jml_waktu' => 'required|string|max:191',
                'type' => 'required|string|max:191',
                'exam_start_date' => 'required|date',
                'exam_end_date' => 'required|date',
                'exam_start_time' => 'required|date_format:H:i',
                'exam_end_time' => 'required|date_format:H:i|after:exam_start_time',
            ]);

            $token = Str::random(5);

            Exam::create([
                'teacher_id' => 1,
                'mapel_id' => $request->mapel_id,
                'name' => $request->name,
                'jml_soal' => $request->jml_soal,
                'jml_waktu' => $request->jml_waktu,
                'type' => $request->type,
                'exam_start' => $request->exam_start_date.' '.$request->exam_start_time,
                'exam_end' => $request->exam_end_date.' '.$request->exam_end_time,
                'token' => $token,
                'created_by' => Auth::id()
            ]);

            return response()->json([
                'status' => true, 'message' => 'Berhasil tersimpan'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Exam::find($id);
        $linkUpdate = route("exams.update", Crypt::encrypt($data->id));
        return response()->json(['data' => $data, 'link' => $linkUpdate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'mapel_id' => 'required|string|max:191',
                'name' => 'required|string|max:191',
                'jml_soal' => 'required|string|max:191',
                'jml_waktu' => 'required|string|max:191',
                'type' => 'required|string|max:191',
                'exam_start_date' => 'required|date',
                'exam_end_date' => 'required|date',
                'exam_start_time' => 'required|date_format:H:i:s',
                'exam_end_time' => 'required|date_format:H:i:s|after:exam_start_time',
            ]);

            $db = Exam::find($id);

            $db->teacher_id = 1;
            $db->mapel_id = $request->mapel_id;
            $db->name = $request->name;
            $db->jml_soal = $request->jml_soal;
            $db->jml_waktu = $request->jml_waktu;
            $db->type = $request->type;
            $db->exam_start = $request->exam_start_date.' '.$request->exam_start_time;
            $db->exam_end = $request->exam_end_date.' '.$request->exam_end_time;
            $db->token = $db->token;

            $db->update();

            return response()->json([
                'status' => true, 'message' => 'Berhasil tersimpan'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Crypt::decrypt($id);
        $data = Exam::where('id', $id);
        $data->delete();
        return true;
    }
}