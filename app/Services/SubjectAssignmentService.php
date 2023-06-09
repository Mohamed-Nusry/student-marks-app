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
                    'subject_id' => 'numeric|required',
                    'title' => 'string|required',
                    'deadline' => 'string|required',
                    'assignment_file' => 'required|file|max:2048',
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
                        if ($request->hasFile('assignment_file')) {
                            $original_filename = $request->file('assignment_file')->getClientOriginalName();
                            $original_filename_arr = explode('.', $original_filename);
                            $file_ext = end($original_filename_arr);
                            $destination_path = './uploads/assignments/';
                            $file_name = 'C-' . time() . '.' . $file_ext;

                            if ($request->file('assignment_file')->move($destination_path, $file_name)) {

                                $uploadPath = '/uploads/assignments/'.$file_name ;


                                $subject_assignment = SubjectAssignment::create([
                                    'subject_id' => $request->subject_id,
                                    'title' => $request->title,
                                    'deadline' => $request->deadline,
                                    'assignment_file' => $uploadPath,
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
