<?php

namespace App\Services;

use App\Models\FavouriteSubject;
use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FavouriteSubjectService {

    public function getAll()
    {
        try {
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

                //Check student subject Exist
                $student_favourite_subject_count = FavouriteSubject::where('student_id', Auth::user()->id)->count();

                if($student_favourite_subject_count > 0){

                    $student_favourite_subject_ids = FavouriteSubject::where('student_id', Auth::user()->id)->pluck('subject_id')->toArray();

                    $student_favourite_subject = Subject::whereIn('id', $student_favourite_subject_ids)->get();

                    $response = [
                        'success' => true,
                        'status' => 200,
                        'message' => 'Data Recieved Successfully',
                        'data' => $student_favourite_subject
                    ];


                }else{
                    $response = [
                        'success' => false,
                        'status' => 400,
                        'message' => 'You have not added any favourite subjects yet',
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
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

                //Check student favourite subject Exist
                $student_favourite_subject_count = FavouriteSubject::where('id', $id)->count();

                if($student_favourite_subject_count > 0){

                    $student_favourite_subject_id = FavouriteSubject::where('id', $id)->first();

                    $student_favourite_subject = Subject::where('id', $student_favourite_subject_id->id)->get();


                    $response = [
                        'success' => true,
                        'status' => 200,
                        'message' => 'Data Recieved Successfully',
                        'data' => $student_favourite_subject
                    ];


                }else{
                    $response = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'Data Not Found',
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
                    'subject_id' => 'required',
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

                //Check student involved with this subject
                $student_subject_count = StudentSubject::where('student_id', Auth::user()->id)->where('subject_id', $request->subject_id)->count();

                if($student_subject_count > 0){

                    //Check already in favourites
                    $student_subject_favourite_count = FavouriteSubject::where('student_id', Auth::user()->id)->where('subject_id', $request->subject_id)->count();

                    if($student_subject_favourite_count > 0){

                        $response = [
                            'success' => false,
                            'status' => 400,
                            'message' => 'This subject is already in the favourites',
                        ];

                    }else{

                        $subject = FavouriteSubject::create([
                            'student_id' => Auth::user()->id,
                            'subject_id' => $request->subject_id,
                        ]);

                        $response = [
                            'success' => true,
                            'status' => 201,
                            'message' => 'Data Created Successfully',
                            'data' => $subject
                        ];

                    }

                }else{
                    $response = [
                        'success' => false,
                        'status' => 400,
                        'message' => 'You are not involved in this subject',
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
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

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

                //Check data exist
                $student_fav_count = FavouriteSubject::where('id', $id)->count();

                if($student_fav_count > 0){

                    //Check student involved with this subject
                    $student_subject_count = StudentSubject::where('student_id', Auth::user()->id)->where('subject_id', $request->subject_id)->count();

                    if($student_subject_count > 0){

                        //Check already in favourites
                        $student_subject_favourite_count = FavouriteSubject::where('student_id', Auth::user()->id)->where('subject_id', $request->subject_id)->count();

                        if($student_subject_favourite_count > 0){

                            $response = [
                                'success' => false,
                                'status' => 400,
                                'message' => 'This subject is already in the favourites',
                            ];

                        }else{

                            $subject = FavouriteSubject::findOrFail($id);
                            $subject->update($request->all());

                            $response = [
                                'success' => true,
                                'status' => 201,
                                'message' => 'Data Created Successfully',
                                'data' => $subject
                            ];

                        }

                    }else{
                        $response = [
                            'success' => false,
                            'status' => 400,
                            'message' => 'You are not involved in this subject',
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


    public function deleteRecord($id)
    {
        try {

            //Check the access
            if(Auth::user()->role_id == Config::get('constants.roles.STUDENT')){

                //Check Data Exist
                $subject_count = FavouriteSubject::where('id', $id)->count();

                if($subject_count > 0){

                    $subject = FavouriteSubject::where('id', $id)->delete();

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
