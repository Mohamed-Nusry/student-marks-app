<?php

namespace App\Services;

use App\Models\TeacherSubject;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class TeacherSubjectService {

    public function getAll()
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                //Check teacher subject Exist
                $teacher_subject_count = TeacherSubject::where('teacher_id', Auth::user()->id)->count();

                if($teacher_subject_count > 0){

                    $teacher_subject_ids = TeacherSubject::where('teacher_id', Auth::user()->id)->pluck('subject_id')->toArray();

                    $teacher_subject = Subject::whereIn('id', $teacher_subject_ids)->with('slides')->with('assignments')->get();

                    $response = [
                        'success' => true,
                        'status' => 200,
                        'message' => 'Data Recieved Successfully',
                        'data' => $teacher_subject
                    ];


                }else{
                    $response = [
                        'success' => false,
                        'status' => 400,
                        'message' => 'You are not assigned in any subject yet',
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
            //Validate Request
            $validateRequest = Validator::make($request->all(),
            [
                'subject_id' => 'numeric|required',
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


            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                //Check Subject Exist
                $subject_count = Subject::where('id', $request->subject_id)->count();

                if($subject_count > 0){

                    //Check already involved
                    $involved_count = TeacherSubject::where('teacher_id', Auth::user()->id)->where('subject_id', $request->subject_id)->count();

                    if($involved_count > 0){

                        $response = [
                            'success' => false,
                            'status' => 404,
                            'message' => 'You Already Assigned To This Subject',
                        ];

                    }else{

                        $teacher_subject = TeacherSubject::create([
                            'teacher_id' => Auth::user()->id,
                            'subject_id' => $request->subject_id,
                        ]);

                        $response = [
                            'success' => true,
                            'status' => 200,
                            'message' => 'Data Created Successfully',
                            'data' => $teacher_subject
                        ];

                    }



                }else{

                    $response = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'Subject Not Found',
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

    public function updateRecord(object $request, $id)
    {
        try {
            //Validate Request
            $validateRequest = Validator::make($request->all(),
            [
                'subject_id' => 'numeric',
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

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                 //Check Subject Exist
                 $subject_count = Subject::where('id', $request->subject_id)->count();
                 $teacher_subject_count = TeacherSubject::where('id', $id)->count();

                 if($subject_count > 0 && $teacher_subject_count > 0){

                     //Check already involved
                     $involved_count = TeacherSubject::where('teacher_id', Auth::user()->id)->where('subject_id', $request->subject_id)->count();

                     if($involved_count > 0){

                        $response = [
                            'success' => false,
                            'status' => 404,
                            'message' => 'You Already Assigned To This Subject',
                        ];

                     }else{

                        $teacher = TeacherSubject::findOrFail($id);
                        $teacher->update($request->all());

                        $response = [
                            'success' => true,
                            'status' => 200,
                            'message' => 'Data Updated Successfully',
                            'data' => $teacher
                        ];

                     }

                }else{

                    $response = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'Subject Record Not Found',
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

    public function deleteRecord($id)
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                //Check Data Exist
                $teacher_subject_count = TeacherSubject::where('id', $id)->count();

                if($teacher_subject_count > 0){

                    $teacher_subject = TeacherSubject::where('id', $id)->delete();

                    $response = [
                        'success' => true,
                        'status' => 200,
                        'message' => 'Data Deleted Successfully',
                    ];

                }else{

                    $response = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'Record Not Found'
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


}
