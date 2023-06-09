<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentService {

    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function viewData($id)
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

                //Check Data Exist
                $student_count = Student::where('user_id', $id)->count();

                if($student_count > 0){

                    $student = Student::where('user_id', $id)->first();

                    $response = [
                        'success' => true,
                        'status' => 200,
                        'message' => 'Data Recieved Successfully',
                        'data' => $student
                    ];

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
             //Validate Request
            $validateRequest = Validator::make($request->all(),
            [
                'first_name' => 'string|required',
                'last_name' => 'string|required',
                'name' => 'required',
                'password' => 'required',
                'email' => 'string|required|unique:students',
                'class_id' => 'numeric|required',
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

            //Register Student

            $registerRequest = [];
            $registerRequest = $request->all();
            $registerRequest['role_id'] = 3;
            $registerRequest['is_enable'] = 1;

            $modified_reg_request = new Request($registerRequest);

            $result = $this->authService->register($modified_reg_request);

            if($result['status'] == 201){

                $student = Student::create([
                    'user_id' => $result['data']['id'],
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'class_id' => $request->class_id,
                ]);

                $response = [
                    'success' => true,
                    'status' => 201,
                    'message' => 'Data Created Successfully',
                    'data' => $student
                ];


            }else{
                $response = [
                    'success' => false,
                    'status' => 400,
                    'message' => $result['message'],
                    'errors' => $result['errors']
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

    // public function createSubject(object $request)
    // {
    //     try {
    //         //Validate Request
    //         $validateRequest = Validator::make($request->all(),
    //         [
    //             'subject_id' => 'numeric|required',
    //         ]);

    //         if($validateRequest->fails()){

    //             $response = [
    //                 'success' => false,
    //                 'status' => 400,
    //                 'message' => 'Validation error',
    //                 'errors' => $validateRequest->errors()
    //             ];

    //             return $response;
    //         }


    //         //Check the access
    //         if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

    //             //Check Subject Exist
    //             $subject_count = Subject::where('id', $request->subject_id)->count();

    //             if($subject_count > 0){

    //                 //Check already involved
    //                 $involved_count = StudentSubject::where('student_id', Auth::user()->id)->where('subject_id', $request->subject_id)->count();

    //                 if($involved_count > 0){

    //                     $response = [
    //                         'success' => false,
    //                         'status' => 404,
    //                         'message' => 'You Already Involved With This Subject',
    //                     ];

    //                 }else{

    //                     $student_subject = StudentSubject::create([
    //                         'student_id' => Auth::user()->id,
    //                         'subject_id' => $request->subject_id,
    //                     ]);

    //                     $response = [
    //                         'success' => true,
    //                         'status' => 200,
    //                         'message' => 'Data Created Successfully',
    //                         'data' => $student_subject
    //                     ];

    //                 }



    //             }else{

    //                 $response = [
    //                     'success' => false,
    //                     'status' => 404,
    //                     'message' => 'Subject Not Found',
    //                 ];

    //             }



    //         }else{

    //             $response = [
    //                 'success' => false,
    //                 'status' => 400,
    //                 'message' => 'Access Denied',
    //             ];

    //         }


    //         return $response;

    //     } catch (\Throwable $th) {

    //         $response = [
    //             'success' => false,
    //             'status' => 500,
    //             'message' => $th->getMessage()
    //         ];

    //         return $response;
    //     }
    // }

    // public function updateSubject(object $request, $id)
    // {
    //     try {
    //         //Validate Request
    //         $validateRequest = Validator::make($request->all(),
    //         [
    //             'subject_id' => 'numeric',
    //         ]);

    //         if($validateRequest->fails()){

    //             $response = [
    //                 'success' => false,
    //                 'status' => 400,
    //                 'message' => 'Validation error',
    //                 'errors' => $validateRequest->errors()
    //             ];

    //             return $response;
    //         }

    //         //Check the access
    //         if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

    //              //Check Subject Exist
    //              $subject_count = Subject::where('id', $request->subject_id)->count();

    //              if($subject_count > 0){

    //                  //Check already involved
    //                  $involved_count = StudentSubject::where('student_id', Auth::user()->id)->where('subject_id', $request->subject_id)->count();

    //                  if($involved_count > 0){

    //                     $response = [
    //                         'success' => false,
    //                         'status' => 404,
    //                         'message' => 'You Already Involved With This Subject',
    //                     ];

    //                  }else{

    //                     $student = StudentSubject::findOrFail($id);
    //                     $student->update($request->all());

    //                     $response = [
    //                         'success' => true,
    //                         'status' => 200,
    //                         'message' => 'Data Updated Successfully',
    //                         'data' => $student
    //                     ];

    //                  }

    //             }

    //         }else{

    //             $response = [
    //                 'success' => false,
    //                 'status' => 400,
    //                 'message' => 'Access Denied',
    //             ];

    //         }

    //         return $response;

    //     } catch (\Throwable $th) {

    //         $response = [
    //             'success' => false,
    //             'status' => 500,
    //             'message' => $th->getMessage()
    //         ];

    //         return $response;
    //     }
    // }

    public function updateRecord(object $request, $id)
    {
        try {
            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){
                //Validate Request
                $validateRequest = Validator::make($request->all(),
                [
                    'email' => [
                        Rule::unique('users')->ignore($id),
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

                $student = Student::where('user_id',$id)->first();
                $student->update($request->all());

                if($request->email && $request->email != null && $request->email != ""){
                    //Update user table also
                    $update_user = User::where('id',$id)->first();
                    $update_user->email = $request->email;
                    $update_user->save();
                }

                $response = [
                    'success' => true,
                    'status' => 200,
                    'message' => 'Data Updated Successfully',
                    'data' => $student
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

}
