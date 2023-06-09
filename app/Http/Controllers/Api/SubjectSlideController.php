<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LectureSlideService;
use Illuminate\Http\Request;

class SubjectSlideController extends Controller
{
    private LectureSlideService $lectureSlideService;

    public function __construct(LectureSlideService $lectureSlideService)
    {
        $this->lectureSlideService = $lectureSlideService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->lectureSlideService->getAll();

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
        $result = $this->lectureSlideService->createRecord($request);

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LectureSlide
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->lectureSlideService->viewData($id);

        return response()->json($result, $result['status']);
    }

    /**
     * Delete the specified resource.
     *
     * @param  \App\Models\LectureSlide
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $result = $this->lectureSlideService->deleteRecord($id);

        return response()->json($result, $result['status']);
    }
}
