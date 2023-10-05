<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Kelas;
use App\Models\Student;
use Illuminate\Http\Request;
use App\DataTables\StudentDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class StudentController extends Controller
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
    public function index(StudentDataTable $dataTable)
    {
        $kelas = Kelas::all();
        return $dataTable->render('backapp.student.index',[
            'kelas'=>$kelas
        ]);
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
                'kelas_id' => 'required|string|max:191',
                'nis' => 'required|string|max:191|unique:students,nis',
                'name' => 'required|string|max:191'
            ]);

            Student::create([
                'kelas_id' => $request->kelas_id,
                'nis' => $request->nis,
                'name' => ucwords($request->name)
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
        $data = Student::find($id);
        $linkUpdate = route("students.update", Crypt::encrypt($data->id));
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
                'kelas_id' => 'required|string|max:191',
                'nis' => 'required|string|max:191|unique:students,nis,'.$id,
                'name' => 'required|string|max:191'
            ]);

            $db = Student::find($id);

            $db->kelas_id = $request->kelas_id;
            $db->nis = $request->nis;
            $db->name = ucwords($request->name);

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
        $data = Student::where('id', $id);
        $data->delete();
        return true;
    }
}