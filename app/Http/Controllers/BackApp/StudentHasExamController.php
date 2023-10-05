<?php

namespace App\Http\Controllers\BackApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\StudentHasExamDataTable;

class StudentHasExamController extends Controller
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
    public function index(StudentHasExamDataTable $dataTable)
    {
        return $dataTable->render('backapp.exam.student');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backapp.exam.enroll');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}