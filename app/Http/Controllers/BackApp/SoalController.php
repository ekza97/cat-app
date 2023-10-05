<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Soal;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\DataTables\SoalDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class SoalController extends Controller
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
    public function index(SoalDataTable $dataTable)
    {
        return $dataTable->render('backapp.question.index');
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
                'teacher_id' => 'required|string|max:191',
                'mapel_id' => 'required|string|max:191',
                'bobot' => 'required|string|max:191',
                'file' => 'nullable|size:2048|mimes:jpg,jpeg,png,mp3,wav',
                'file_type' => 'nullable|string',
                'soal' => 'required|string|max:191',
                'opsi_a' => 'required|string|max:191',
                'opsi_b' => 'required|string|max:191',
                'opsi_c' => 'required|string|max:191',
                'opsi_d' => 'required|string|max:191',
                'jawaban' => 'required|string|max:191',
                'soal_type' => 'required|string|max:191'
            ]);

            if($request->hasFile('file')){
                $file = $request->file('file')->store('public/fileSoal');
                $ext = $request->file('file')->extension();
            }else{
                $file = null;
                $ext = null;
            }

            Soal::create([
                'teacher_id' => $request->teacher_id,
                'mapel_id' => $request->mapel_id,
                'bobot' => $request->bobot,
                'file' => Helper::getFilename($file),
                'file_type' => $ext,
                'soal' => $request->soal,
                'opsi_a' => $request->opsi_a,
                'opsi_b' => $request->opsi_b,
                'opsi_c' => $request->opsi_c,
                'opsi_d' => $request->opsi_d,
                'jawaban' => $request->jawaban,
                'soal_type' => $request->soal_type
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
        $data = Soal::find($id);
        $linkUpdate = route("questions.update", Crypt::encrypt($data->id));
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
                'teacher_id' => 'required|string|max:191',
                'mapel_id' => 'required|string|max:191',
                'bobot' => 'required|string|max:191',
                'file' => 'nullable|size:2048|mimes:jpg,jpeg,png,mp3,wav',
                'file_type' => 'nullable|string',
                'soal' => 'required|string|max:191',
                'opsi_a' => 'required|string|max:191',
                'opsi_b' => 'required|string|max:191',
                'opsi_c' => 'required|string|max:191',
                'opsi_d' => 'required|string|max:191',
                'jawaban' => 'required|string|max:191',
                'soal_type' => 'required|string|max:191'
            ]);

            $db = Soal::find($id);

            if($request->hasFile('file')){
                Storage::disk('public')->delete('fileSoal/'.$db->file);
                $file = $request->file('file')->store('public/fileSoal');
                $ext = $request->file('file')->extension();
            }else{
                $file = $db->file;
                $ext = $db->file_type;
            }

            $db->teacher_id = $request->teacher_id;
            $db->mapel_id = $request->mapel_id;
            $db->bobot = $request->bobot;
            $db->file = Helper::getFilename($file);
            $db->file_type = $ext;
            $db->soal = $request->soal;
            $db->opsi_a = $request->opsi_a;
            $db->opsi_b = $request->opsi_b;
            $db->opsi_c = $request->opsi_c;
            $db->opsi_d = $request->opsi_d;
            $db->jawaban = $request->jawaban;
            $db->soal_type = $request->soal_type;

            $db->update();

            return response()->json([
                'status' => true, 'message' => 'Berhasil update'
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
        $data = Soal::where('id', $id);
        $data->delete();
        return true;
    }
}