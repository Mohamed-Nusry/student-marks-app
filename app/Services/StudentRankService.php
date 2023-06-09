<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentRank;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentRankService {

    public function getAll()
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                $student_rank = StudentRank::with('subject')->with('student')->get();

                $response = [
                    'success' => true,
                    'status' => 200,
                    'message' => 'Data Recieved Successfully',
                    'data' => $student_rank
                ];

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

                //Check Data Exist
                $student_rank_count = StudentRank::where('student_id', $id)->count();

                if($student_rank_count > 0){

                    //Check whether student belongs to teacher
                    $student_count = Student::where('user_id', $id)->count();

                    if($student_count > 0){

                        $get_student_data = Student::where('user_id', $id)->first();
                        $get_teacher_data = Teacher::where('user_id', Auth::user()->id)->first();

                        if($get_student_data->class_id != $get_teacher_data->class_id){

                            $response = [
                                'success' => false,
                                'status' => 404,
                                'message' => 'This student is not belong to your class or subject',
                            ];

                        }else{

                            $student_rank = StudentRank::with('subject')->with('student')->where('student_id', $id)->first();

                            $response = [
                                'success' => true,
                                'status' => 200,
                                'message' => 'Data Recieved Successfully',
                                'data' => $student_rank
                            ];

                        }
                    }else{
                        $response = [
                            'success' => false,
                            'status' => 404,
                            'message' => 'Student not found',
                        ];
                    }





                }else{

                    $response = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'Record Not Found',
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
                    'student_id' => 'numeric|required',
                    'subject_id' => 'numeric|required',
                    'marks' => 'string|required',
                    'rank' => 'string|required',
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


                 //Check teacher subject Exist
                 $teacher_subject_count = TeacherSubject::where('teacher_id', Auth::user()->id)->count();

                 if($teacher_subject_count > 0){

                     //Check whether subject belongs to teacher
                     $teacher_subject_ids = TeacherSubject::where('teacher_id', Auth::user()->id)->pluck('subject_id')->toArray();

                     if(in_array($request->subject_id, $teacher_subject_ids)){

                        //Check whether student belongs to teacher
                        $student_count = Student::where('user_id', $request->student_id)->count();

                        if($student_count > 0){

                            $get_student_data = Student::where('user_id', $request->student_id)->first();
                            $get_teacher_data = Teacher::where('user_id', Auth::user()->id)->first();

                            if($get_student_data->class_id != $get_teacher_data->class_id){

                                $response = [
                                    'success' => false,
                                    'status' => 404,
                                    'message' => 'This student is not belong to your class or subject',
                                ];

                            }else{
                                //Proceed
                                $student_rank = StudentRank::create([
                                    'student_id' => $request->student_id,
                                    'subject_id' => $request->subject_id,
                                    'marks' => $request->marks,
                                    'rank' => $request->rank,
                                ]);

                                $response = [
                                    'success' => true,
                                    'status' => 201,
                                    'message' => 'Data Created Successfully',
                                    'data' => $student_rank
                                ];

                            }



                        }else{
                            $response = [
                                'success' => false,
                                'status' => 404,
                                'message' => 'Student not found',
                            ];
                        }


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
                    'student_id' => 'numeric|required',
                    'subject_id' => 'numeric|required',
                    'marks' => 'string',
                    'rank' => 'string',
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


                 //Check teacher subject Exist
                 $teacher_subject_count = TeacherSubject::where('teacher_id', Auth::user()->id)->count();

                 if($teacher_subject_count > 0){

                     //Check whether subject belongs to teacher
                     $teacher_subject_ids = TeacherSubject::where('teacher_id', Auth::user()->id)->pluck('subject_id')->toArray();

                     if(in_array($request->subject_id, $teacher_subject_ids)){

                        //Check whether student belongs to teacher
                        $student_count = Student::where('user_id', $request->student_id)->count();

                        if($student_count > 0){

                            $get_student_data = Student::where('user_id', $request->student_id)->first();
                            $get_teacher_data = Teacher::where('user_id', Auth::user()->id)->first();

                            if($get_student_data->class_id != $get_teacher_data->class_id){

                                $response = [
                                    'success' => false,
                                    'status' => 404,
                                    'message' => 'This student is not belong to your class or subject',
                                ];

                            }else{
                                //Proceed
                                $student_rank = StudentRank::findOrFail($id);
                                $student_rank->update($request->all());

                                $response = [
                                    'success' => true,
                                    'status' => 201,
                                    'message' => 'Data Updated Successfully',
                                    'data' => $student_rank
                                ];

                            }



                        }else{
                            $response = [
                                'success' => false,
                                'status' => 404,
                                'message' => 'Student not found',
                            ];
                        }


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

}
