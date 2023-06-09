<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StudentSubjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSubjectController extends Controller
{
    private StudentSubjectService $studentSubjectService;

    public function __construct(StudentSubjectService $studentSubjectService)
    {
        $this->studentSubjectService = $studentSubjectService;
    }


     /**
     * Add Subject to student
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->studentSubjectService->createRecord($request);

        return response()->json($result, $result['status']);
    }

     /**
     * Update Subject to student
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->studentSubjectService->updateRecord($request, $id);

        return response()->json($result, $result['status']);
    }

     /**
     * Delete the specified resource.
     *
     * @param  \App\Models\Subject
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $result = $this->studentSubjectService->deleteRecord($id);

        return response()->json($result, $result['status']);
    }


}
