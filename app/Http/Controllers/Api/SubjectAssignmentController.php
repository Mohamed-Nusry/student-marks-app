<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubjectAssignmentService;
use Illuminate\Http\Request;

class SubjectAssignmentController extends Controller
{
    private SubjectAssignmentService $subjectAssignmentService;

    public function __construct(SubjectAssignmentService $subjectAssignmentService)
    {
        $this->subjectAssignmentService = $subjectAssignmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->subjectAssignmentService->getAll();

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
        $result = $this->subjectAssignmentService->createRecord($request);

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubjectAssignment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->subjectAssignmentService->viewData($id);

        return response()->json($result, $result['status']);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Models\SubjectAssignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->subjectAssignmentService->updateRecord($request, $id);

        return response()->json($result, $result['status']);
    }

    /**
     * Delete the specified resource.
     *
     * @param  \App\Models\SubjectAssignment
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $result = $this->subjectAssignmentService->deleteRecord($id);

        return response()->json($result, $result['status']);
    }
}
