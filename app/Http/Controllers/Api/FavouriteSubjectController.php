<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FavouriteSubjectService;
use Illuminate\Http\Request;

class FavouriteSubjectController extends Controller
{
    private FavouriteSubjectService $favouriteSubjectService;

    public function __construct(FavouriteSubjectService $favouriteSubjectService)
    {
        $this->favouriteSubjectService = $favouriteSubjectService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->favouriteSubjectService->getAll();

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
        $result = $this->favouriteSubjectService->createRecord($request);

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FavouriteSubject
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->favouriteSubjectService->viewData($id);

        return response()->json($result, $result['status']);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Models\FavouriteSubject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->favouriteSubjectService->updateRecord($request, $id);

        return response()->json($result, $result['status']);
    }

    /**
     * Delete the specified resource.
     *
     * @param  \App\Models\FavouriteSubject
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $result = $this->favouriteSubjectService->deleteRecord($id);

        return response()->json($result, $result['status']);
    }
}
