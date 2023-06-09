<?php

namespace App\Services;

use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeacherService {

    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function viewData($id)
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){

                //Check Data Exist
                $teacher_count = Teacher::where('user_id', $id)->count();

                if($teacher_count > 0){

                    $teacher = Teacher::where('user_id', $id)->first();

                    $response = [
                        'success' => true,
                        'status' => 200,
                        'message' => 'Data Recieved Successfully',
                        'data' => $teacher
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
                'email' => 'string|required|unique:teachers',
                'dob' => 'required',
                'qualification' => 'string|required',
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

            //Register Teacher

            $registerRequest = [];
            $registerRequest = $request->all();
            $registerRequest['role_id'] = 2;
            $registerRequest['is_enable'] = 1;

            $modified_reg_request = new Request($registerRequest);

            $result = $this->authService->register($modified_reg_request);

            if($result['status'] == 201){

                $teacher = Teacher::create([
                    'user_id' => $result['data']['id'],
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'dob' => $request->dob,
                    'qualification' => $request->qualification,
                    'class_id' => $request->class_id,
                ]);

                $response = [
                    'success' => true,
                    'status' => 201,
                    'message' => 'Data Created Successfully',
                    'data' => $teacher
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

    public function updateRecord(object $request, $id)
    {
        try {
            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.TEACHER')){
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

                $teacher = Teacher::where('user_id',$id)->first();
                $teacher->update($request->all());

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
                    'data' => $teacher
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
