<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class RoleController extends Controller
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
    public function index(RoleDataTable $dataTable)
    {
        $modules = [
            'all_permission'        => 'Administrator Permissions',
            'kontak_kami'          => 'Kontak Kami',
            'dashboard'          => 'Dashboard Management',
            'template_doc'          => 'Master Template Management',
            'tanda_tangan'          => 'Master Tanda Tangan Management',
            'unit_kerja'            => 'Unit Kerja Management',
            'jenis_surat'           => 'Jenis Surat Management',
            'surats'                 => 'Surat Management',
            'permission'            => 'Permission Management',
            'role'                  => 'Role Management',
            'user'                  => 'User Management',
        ];
        $setting = true;
        return $dataTable->render('backapp.settings.role.index',compact('modules','setting'));
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
                'name' => 'required|alpha_dash|min:3|unique:roles,name',
                'permission' => 'required|array'
            ]);

            $insert = Role::create([
                'name' => $request->name,
            ]);
            $insert->givePermissionTo($request->permission);

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
        $data = Role::with('permissions')->find($id);
        $linkUpdate = route("roles.update", Crypt::encrypt($data->id));
        return response()->json(['name' => $data->name, 'permission'=>$data->permissions, 'link' => $linkUpdate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'name' => 'required|alpha_dash|min:3|unique:roles,name,' . $id,
                'permission' => 'required|array'
            ]);

            $db = Role::find($id);

            $db->name = $request->name;

            $db->update();
            $db->syncPermissions($request->permission);

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
        $data = Role::where('id', $id);
        $data->delete();
        return true;
    }
}