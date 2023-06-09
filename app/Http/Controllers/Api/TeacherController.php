<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    private TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->teacherService->viewData(Auth::user()->id);

        return response()->json($result, $result['status']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->teacherService->createRecord($request);

        return response()->json($result, $result['status']);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Models\Teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->teacherService->updateRecord($request, $id);

        return response()->json($result, $result['status']);
    }

      /**
     * Display all students related to teacher.
     *
     * @param  \App\Models\Teacher
     * @return \Illuminate\Http\Response
     */
    public function student()
    {
        $result = $this->teacherService->getStudents();

        return response()->json($result, $result['status']);
    }


}
