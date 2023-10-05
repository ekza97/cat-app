<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\DataTables\TeacherDataTable;
use App\Http\Controllers\Controller;
use App\Models\TeacherHasMapel;
use Illuminate\Support\Facades\Crypt;

class TeacherController extends Controller
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
    public function index(TeacherDataTable $dataTable)
    {
        return $dataTable->render('backapp.teacher.index');
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
                'nik' => 'required|string|max:191|unique:teachers,nik',
                'name' => 'required|string|max:191'
            ]);

            Teacher::create([
                'nik' => $request->nik,
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
        $data = TeacherHasMapel::where('teacher_id',$id)->get();
        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Teacher::find($id);
        $linkUpdate = route("teachers.update", Crypt::encrypt($data->id));
        return response()->json(['nik' => $data->nik, 'name'=>$data->name, 'link' => $linkUpdate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'nik' => 'required|string|max:191|unique:teachers,nik,'.$id,
                'name' => 'required|string|max:191'
            ]);

            $db = Teacher::find($id);

            $db->nik = $request->nik;
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
        $data = Teacher::where('id', $id);
        $data->delete();
        return true;
    }

    public function setMapel(Request $request){

        try {
            $teacher_id = $request->teacher_id;
            TeacherHasMapel::where('teacher_id',$teacher_id)->delete();
            foreach($request->mapel as $item){
                TeacherHasMapel::Create([
                    'teacher_id'=> $teacher_id,
                    'mapel_id'=>$item
                ]);
            }

            return response()->json([
                'status' => true, 'message' => 'Berhasil mengatur Mata Pelajaran'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
