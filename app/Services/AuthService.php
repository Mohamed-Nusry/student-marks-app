<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthService {

    public function login(object $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){

                $response = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ];

                return $response;

            }

            if(!Auth::attempt($request->only(['email', 'password']))){

                $response = [
                    'success' => false,
                    'status' => 401,
                    'message' => 'Invalid Credentials.',
                ];

                return $response;
            }

            $user = User::where('email', $request->email)->first();

            if($user->is_enable != 1){

                return $response = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'Your account is blocked. Please contact admin',
                ];

            }

            $response = [
                'success' => true,
                'status' => 200,
                'message' => 'Logged in Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
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

    public function register(object $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){

                $response = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ];

                return $response;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'is_enable' => $request->is_enable,
            ]);

            $response = [
                'success' => true,
                'status' => 201,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'data' => $user
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

    public function blockUser(object $request)
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.ADMIN')){

                //Validate Request
                $validateRequest = Validator::make($request->all(),
                [
                    'user_id' => 'required',
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

                //Check User Exist
                $check_user_count = User::where('id', $request->user_id)->count();

                if($check_user_count > 0){

                    //Get User
                    $get_user = User::where('id', $request->user_id)->first();

                    if($get_user->is_enable == 0){

                        $response = [
                            'success' => false,
                            'status' => 400,
                            'message' => 'User Already Blocked',
                        ];

                    }else{
                        $get_user->is_enable = 0;
                        $get_user->save();

                        $response = [
                            'success' => true,
                            'status' => 201,
                            'message' => 'User Blocked Successfully',
                        ];
                    }

                }else{

                    $response = [
                        'success' => false,
                        'status' => 400,
                        'message' => 'User Not Found',
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


    public function unblockUser(object $request)
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.ADMIN')){

                //Validate Request
                $validateRequest = Validator::make($request->all(),
                [
                    'user_id' => 'required',
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

                //Check User Exist
                $check_user_count = User::where('id', $request->user_id)->count();

                if($check_user_count > 0){

                    //Get User
                    $get_user = User::where('id', $request->user_id)->first();

                    if($get_user->is_enable == 1){

                        $response = [
                            'success' => false,
                            'status' => 400,
                            'message' => 'User Already Active',
                        ];

                    }else{
                        $get_user->is_enable = 1;
                        $get_user->save();

                        $response = [
                            'success' => true,
                            'status' => 201,
                            'message' => 'User Unblocked Successfully',
                        ];
                    }

                }else{

                    $response = [
                        'success' => false,
                        'status' => 400,
                        'message' => 'User Not Found',
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
