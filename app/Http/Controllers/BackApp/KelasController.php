<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\DataTables\KelasDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class KelasController extends Controller
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
    public function index(KelasDataTable $dataTable)
    {
        return $dataTable->render('backapp.classroom.index');
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
                'name' => 'required|string|max:191'
            ]);

            Kelas::create([
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
        $data = Kelas::find($id);
        $linkUpdate = route("classroom.update", Crypt::encrypt($data->id));
        return response()->json(['name' => $data->name, 'link' => $linkUpdate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'name' => 'required|string|max:191'
            ]);

            $db = Kelas::find($id);

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
        $data = Kelas::where('id', $id);
        $data->delete();
        return true;
    }
}