<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    private SubjectService $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->subjectService->getAll();

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
        $result = $this->subjectService->createRecord($request);

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->subjectService->viewData($id);

        return response()->json($result, $result['status']);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Models\Subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->subjectService->updateRecord($request, $id);

        return response()->json($result, $result['status']);
    }
}
