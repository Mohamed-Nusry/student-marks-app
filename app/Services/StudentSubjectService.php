<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentSubjectService {

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
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

                //Check Subject Exist
                $subject_count = Subject::where('id', $request->subject_id)->count();

                if($subject_count > 0){

                    //Check already involved
                    $involved_count = StudentSubject::where('student_id', Auth::user()->id)->where('subject_id', $request->subject_id)->count();

                    if($involved_count > 0){

                        $response = [
                            'success' => false,
                            'status' => 404,
                            'message' => 'You Already Involved With This Subject',
                        ];

                    }else{

                        $student_subject = StudentSubject::create([
                            'student_id' => Auth::user()->id,
                            'subject_id' => $request->subject_id,
                        ]);

                        $response = [
                            'success' => true,
                            'status' => 200,
                            'message' => 'Data Created Successfully',
                            'data' => $student_subject
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
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

                 //Check Subject Exist
                 $subject_count = Subject::where('id', $request->subject_id)->count();
                 $student_subject_count = StudentSubject::where('id', $id)->count();

                 if($subject_count > 0 && $student_subject_count > 0){

                     //Check already involved
                     $involved_count = StudentSubject::where('student_id', Auth::user()->id)->where('subject_id', $request->subject_id)->count();

                     if($involved_count > 0){

                        $response = [
                            'success' => false,
                            'status' => 404,
                            'message' => 'You Already Involved With This Subject',
                        ];

                     }else{

                        $student = StudentSubject::findOrFail($id);
                        $student->update($request->all());

                        $response = [
                            'success' => true,
                            'status' => 200,
                            'message' => 'Data Updated Successfully',
                            'data' => $student
                        ];

                     }

                }else{

                    $response = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'Subject Record Not Found',
                    ];

                }

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

            //Check Data Exist
            $student_subject_count = StudentSubject::where('id', $id)->count();

            if($student_subject_count > 0){

                $student_subject = StudentSubject::where('id', $id)->delete();

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
