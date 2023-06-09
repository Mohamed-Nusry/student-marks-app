<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StudentRankService;
use Illuminate\Http\Request;

class StudentRankController extends Controller
{
    private StudentRankService $studentRankService;

    public function __construct(StudentRankService $studentRankService)
    {
        $this->studentRankService = $studentRankService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->studentRankService->getAll();

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
        $result = $this->studentRankService->createRecord($request);

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentRank
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->studentRankService->viewData($id);

        return response()->json($result, $result['status']);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Models\StudentRank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->studentRankService->updateRecord($request, $id);

        return response()->json($result, $result['status']);
    }

}
