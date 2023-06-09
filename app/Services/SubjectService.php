<?php

namespace App\Services;

use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubjectService {

    public function getAll()
    {
        try {

            if(Auth::check()){
                $subject = Subject::with('slides')->with('assignments')->get();
            }else{
                $subject = Subject::all();
            }

            $response = [
                'success' => true,
                'status' => 200,
                'message' => 'Data Recieved Successfully',
                'data' => $subject
            ];

            return $response;

        } catch (\Throwable $th) {

            $response = [
                'success' => false,
                'status' => 500,
                'message' => $th->getMessage()
            ];

            return $response;

        }
    }

    public function viewData($id)
    {
        try {

            //Check Data Exist
            $subject_count = Subject::where('id', $id)->count();

            if(Auth::check()){
                $subject = Subject::with('slides')->with('assignments')->where('id', $id)->first();
            }else{
                $subject = Subject::where('id', $id)->first();
            }

            if($subject_count > 0){

                $response = [
                    'success' => true,
                    'status' => 200,
                    'message' => 'Data Recieved Successfully',
                    'data' => $subject
                ];

                return $response;

            }else{

                $response = [
                    'success' => false,
                    'status' => 404,
                    'message' => 'Record Not Found',
                    'data' => $subject
                ];

                return $response;

            }



        } catch (\Throwable $th) {

            $response = [
                'success' => false,
                'status' => 500,
                'message' => $th->getMessage()
            ];

            return $response;

        }
    }

    public function createRecord(object $request)
    {
        try {
             //Validate Request
            $validateRequest = Validator::make($request->all(),
            [
                'name' => 'required|unique:subjects',
            ]);

            if($validateRequest->fails()){

                $response = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'Validation error',
                    'errors' => $validateRequest->errors()
                ];

                return $response;
            }

            $subject = Subject::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            $response = [
                'success' => true,
                'status' => 201,
                'message' => 'Data Created Successfully',
                'data' => $subject
            ];

            return $response;

        } catch (\Throwable $th) {

            $response = [
                'success' => false,
                'status' => 500,
                'message' => $th->getMessage()
            ];

            return $response;
        }
    }

    public function updateRecord(object $request, $id)
    {
        try {
            //Validate Request
            $validateRequest = Validator::make($request->all(),
            [
                'name' => [
                    Rule::unique('subjects')->ignore($id),
                ],
            ]);

            if($validateRequest->fails()){

                $response = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'Validation error',
                    'errors' => $validateRequest->errors()
                ];

                return $response;
            }

            $subject = Subject::findOrFail($id);
            $subject->update($request->all());

            $response = [
                'success' => true,
                'status' => 200,
                'message' => 'Data Updated Successfully',
                'data' => $subject
            ];

            return $response;

        } catch (\Throwable $th) {

            $response = [
                'success' => false,
                'status' => 500,
                'message' => $th->getMessage()
            ];

            return $response;
        }
    }
}
