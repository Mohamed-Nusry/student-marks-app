<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
}
