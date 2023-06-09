<?php

namespace App\Services;

use App\Models\AssignmentSubmission;
use App\Models\StudentSubject;
use App\Models\SubjectAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AssignmentSubmissionService {

    public function getAll()
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

                //Check student subject Exist
                $student_subject_count = StudentSubject::where('student_id', Auth::user()->id)->count();

                if($student_subject_count > 0){

                    $student_subject_ids = StudentSubject::where('student_id', Auth::user()->id)->pluck('subject_id')->toArray();

                    $subject_assignment = AssignmentSubmission::whereIn('subject_id', $student_subject_ids)->with('subject')->with('assignment')->get();


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
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

                //Check student subject Exist
                $student_subject_count = StudentSubject::where('student_id', Auth::user()->id)->count();

                //Get Subject ID Count
                $subject_assignment_dta_count = SubjectAssignment::where('id', $id)->count();


                if($student_subject_count > 0){

                    if($subject_assignment_dta_count > 0){

                        //Get Subject ID
                       $subject_assignment_dta = SubjectAssignment::where('id', $id)->first();

                        $student_subject_ids = StudentSubject::where('student_id', Auth::user()->id)->pluck('subject_id')->toArray();

                        if(in_array($subject_assignment_dta->subject_id, $student_subject_ids)){

                            $subject_assignment = AssignmentSubmission::where('subject_assignment_id', $id)->with('subject')->with('assignment')->get();


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
                            'message' => 'Assignment not found',
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
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

                //Validate Request
                $validateRequest = Validator::make($request->all(),
                [
                    'subject_assignment_id' => 'numeric|required',
                    'submission_file' => 'required|file|max:2048',
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

                //Check student subject Exist
                $student_subject_count = StudentSubject::where('student_id', Auth::user()->id)->count();

                //Get Subject ID Count
                $subject_assignment_dta_count = SubjectAssignment::where('id', $request->subject_assignment_id)->count();


                if($student_subject_count > 0){

                    if($subject_assignment_dta_count > 0){

                         //Get Subject ID
                        $subject_assignment_dta = SubjectAssignment::where('id', $request->subject_assignment_id)->first();

                        $student_subject_ids = StudentSubject::where('student_id', Auth::user()->id)->pluck('subject_id')->toArray();

                        if(in_array($subject_assignment_dta->subject_id, $student_subject_ids)){

                            //Upload File
                            if ($request->hasFile('submission_file')) {
                                $original_filename = $request->file('submission_file')->getClientOriginalName();
                                $original_filename_arr = explode('.', $original_filename);
                                $file_ext = end($original_filename_arr);
                                $destination_path = './uploads/submissions/';
                                $file_name = 'C-' . time() . '.' . $file_ext;

                                if ($request->file('submission_file')->move($destination_path, $file_name)) {

                                    $uploadPath = '/uploads/submissions/'.$file_name ;


                                    $subject_assignment = AssignmentSubmission::create([
                                        'student_id' => Auth::user()->id,
                                        'subject_assignment_id' => $request->subject_assignment_id,
                                        'subject_id' => $subject_assignment_dta->subject_id,
                                        'title' => $request->title,
                                        'deadline' => $request->deadline,
                                        'submission_file' => $uploadPath,
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
                            'message' => 'Assignment not found',
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
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

                //Check Data Exist
                $subject_assignment_count = AssignmentSubmission::where('id', $id)->count();

                if($subject_assignment_count > 0){

                    $subject_assignment = AssignmentSubmission::where('id', $id)->first();

                    $student_subject_ids = StudentSubject::where('student_id', Auth::user()->id)->pluck('subject_id')->toArray();

                    if(in_array($subject_assignment->subject_id, $student_subject_ids)){

                        $subject_assignment_delete = AssignmentSubmission::where('id', $id)->delete();

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
