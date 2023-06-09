<?php

namespace App\Services;

use App\Models\LectureSlide;
use App\Models\TeacherSubject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class LectureSlideService {

    public function getAll()
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                //Check teacher subject Exist
                $teacher_subject_count = TeacherSubject::where('teacher_id', Auth::user()->id)->count();

                if($teacher_subject_count > 0){

                    $teacher_subject_ids = TeacherSubject::where('teacher_id', Auth::user()->id)->pluck('subject_id')->toArray();

                    $subject_assignment = LectureSlide::whereIn('subject_id', $teacher_subject_ids)->with('subject')->get();


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

                        $subject_assignment = LectureSlide::where('subject_id', $id)->with('subject')->get();


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
                    'subject_id' => 'numeric|required',
                    'slide_file' => 'required|file|max:2048',
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

                    $teacher_subject_ids = TeacherSubject::where('teacher_id', Auth::user()->id)->pluck('subject_id')->toArray();

                    if(in_array($request->subject_id, $teacher_subject_ids)){

                        //Upload File
                        if ($request->hasFile('slide_file')) {
                            $original_filename = $request->file('slide_file')->getClientOriginalName();
                            $original_filename_arr = explode('.', $original_filename);
                            $file_ext = end($original_filename_arr);
                            $destination_path = './uploads/slides/';
                            $file_name = 'C-' . time() . '.' . $file_ext;

                            if ($request->file('slide_file')->move($destination_path, $file_name)) {

                                $uploadPath = '/uploads/slides/'.$file_name ;


                                $subject_assignment = LectureSlide::create([
                                    'subject_id' => $request->subject_id,
                                    'title' => $request->title,
                                    'deadline' => $request->deadline,
                                    'slide_file' => $uploadPath,
                                ]);

                                $response = [
                                    'success' => true,
                                    'status' => 201,
                                    'message' => 'Data Created Successfully',
                                    'data' => $subject_assignment
                                ];

                            } else {
                                $response = [
                                    'success' => false,
                                    'status' => 400,
                                    'message' => 'Error while uploading',
                                ];
                            }
                        }else{
                            $response = [
                                'success' => false,
                                'status' => 400,
                                'message' => 'Error while uploading',
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

    public function deleteRecord($id)
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                //Check Data Exist
                $subject_assignment_count = LectureSlide::where('id', $id)->count();

                if($subject_assignment_count > 0){

                    $subject_assignment = LectureSlide::where('id', $id)->first();

                    $teacher_subject_ids = TeacherSubject::where('teacher_id', Auth::user()->id)->pluck('subject_id')->toArray();

                    if(in_array($subject_assignment->subject_id, $teacher_subject_ids)){

                        $subject_assignment_delete = LectureSlide::where('id', $id)->delete();

                        $response = [
                            'success' => true,
                            'status' => 200,
                            'message' => 'Data Deleted Successfully',
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
