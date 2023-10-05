<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Mapel;
use Illuminate\Http\Request;
use App\DataTables\MapelDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class MapelController extends Controller
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
    public function index(MapelDataTable $dataTable)
    {
        return $dataTable->render('backapp.course.index');
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
                'code' => 'required|string|max:191',
                'description' => 'required|string'
            ]);

            Mapel::create([
                'code' => strtoupper($request->code),
                'description' => ucwords($request->description)
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
        $data = Mapel::find($id);
        $linkUpdate = route("courses.update", Crypt::encrypt($data->id));
        return response()->json(['code' => $data->code, 'description'=>$data->description, 'link' => $linkUpdate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'code' => 'required|string|max:191',
                'description' => 'required|string'
            ]);

            $db = Mapel::find($id);

            $db->code = strtoupper($request->code);
            $db->description = ucwords($request->description);

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
        $data = Mapel::where('id', $id);
        $data->delete();
        return true;
    }
}