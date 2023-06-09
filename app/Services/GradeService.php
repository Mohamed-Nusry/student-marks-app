<?php

namespace App\Services;

use App\Models\Grade;

class GradeService {

    public function getAll()
    {
        try {


            $grade = Grade::all();


            $response = [
                'success' => true,
                'status' => 200,
                'message' => 'Data Recieved Successfully',
                'data' => $grade
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
