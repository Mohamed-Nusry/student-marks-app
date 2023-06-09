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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->studentSubjectService->getAll();

        return response()->json($result, $result['status']);
    }

    /**
     * Display a all subject ranks.
     *
     * @return \Illuminate\Http\Response
     */
    public function rank()
    {
        $result = $this->studentSubjectService->getAllRanks();

        return response()->json($result, $result['status']);
    }

    /**
     * Display a all subject rank by subject.
     *
     * @return \Illuminate\Http\Response
     */
    public function rankBySubject($id)
    {
        $result = $this->studentSubjectService->getRankBySubject($id);

        return response()->json($result, $result['status']);
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
