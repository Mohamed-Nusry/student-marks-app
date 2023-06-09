<?php

namespace App\Services;

use App\Models\SubjectAssignment;
use App\Models\TeacherSubject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubjectAssignmentService {

    public function getAll()
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                //Check teacher subject Exist
                $teacher_subject_count = TeacherSubject::where('teacher_id', Auth::user()->id)->count();

                if($teacher_subject_count > 0){

                    $teacher_subject_ids = TeacherSubject::where('teacher_id', Auth::user()->id)->pluck('subject_id')->toArray();

                    $subject_assignment = SubjectAssignment::whereIn('subject_id', $teacher_subject_ids)->with('subject')->get();


                    $response = [
                        'success' => true,
                        'status' => 200,
                        'message' => 'Data Recieved Successfully',
                        'data' => $subject_assignment
                    ];

                }else{
                    $response = [
                        'success' => false,
                        'status' => 400,
                        'message' => 'You have no subjects assigned',
                    ];
                }



            }else{

                $response = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'Access Denied',
                ];

            }

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

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                //Check teacher subject Exist
                $teacher_subject_count = TeacherSubject::where('teacher_id', Auth::user()->id)->count();

                if($teacher_subject_count > 0){

                    $teacher_subject_ids = TeacherSubject::where('teacher_id', Auth::user()->id)->pluck('subject_id')->toArray();

                    if(in_array($id, $teacher_subject_ids)){

                        $subject_assignment = SubjectAssignment::where('subject_id', $id)->with('subject')->get();


                        $response = [
                            'success' => true,
                            'status' => 200,
                            'message' => 'Data Recieved Successfully',
                            'data' => $subject_assignment
                        ];

                    }else{

                        $response = [
                            'success' => false,
                            'status' => 400,
                            'message' => 'This subject is not assigned to you',
                        ];

                    }



                }else{
                    $response = [
                        'success' => false,
                        'status' => 400,
                        'message' => 'You have no subjects assigned',
                    ];
                }



            }else{

                $response = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'Access Denied',
                ];

            }

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

    public function createRecord(object $request)
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                //Validate Request
                $validateRequest = Validator::make($request->all(),
                [
                    'name' => 'required|unique:subject_assignments',
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

                $subject_assignment = SubjectAssignment::create([
                    'name' => $request->name,
                    'description' => $request->description,
                ]);

                $response = [
                    'success' => true,
                    'status' => 201,
                    'message' => 'Data Created Successfully',
                    'data' => $subject_assignment
                ];

                return $response;

            }else{

                $response = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'Access Denied',
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

    public function updateRecord(object $request, $id)
    {

        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                //Validate Request
                $validateRequest = Validator::make($request->all(),
                [
                    'name' => [
                        Rule::unique('subject_assignments')->ignore($id),
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

                $subject_assignment = SubjectAssignment::findOrFail($id);
                $subject_assignment->update($request->all());

                $response = [
                    'success' => true,
                    'status' => 200,
                    'message' => 'Data Updated Successfully',
                    'data' => $subject_assignment
                ];

                return $response;

            }else{

                $response = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'Access Denied',
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


    public function deleteRecord($id)
    {
        try {

            //Check Data Exist
            $subject_assignment_count = SubjectAssignment::where('id', $id)->count();

            if($subject_assignment_count > 0){

                $subject_assignment = SubjectAssignment::where('id', $id)->delete();

                $response = [
                    'success' => true,
                    'status' => 200,
                    'message' => 'Data Deleted Successfully',
                ];

                return $response;

            }else{

                $response = [
                    'success' => false,
                    'status' => 404,
                    'message' => 'Record Not Found'
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

}
