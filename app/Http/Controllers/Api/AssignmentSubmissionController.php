<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AssignmentSubmissionService;
use Illuminate\Http\Request;

class AssignmentSubmissionController extends Controller
{
    private AssignmentSubmissionService $assignmentSubmissionService;

    public function __construct(AssignmentSubmissionService $assignmentSubmissionService)
    {
        $this->assignmentSubmissionService = $assignmentSubmissionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->assignmentSubmissionService->getAll();

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
        $result = $this->assignmentSubmissionService->createRecord($request);

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AssignmentSubmission
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->assignmentSubmissionService->viewData($id);

        return response()->json($result, $result['status']);
    }

    /**
     * Delete the specified resource.
     *
     * @param  \App\Models\AssignmentSubmission
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $result = $this->assignmentSubmissionService->deleteRecord($id);

        return response()->json($result, $result['status']);
    }
}
