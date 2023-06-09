<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grades')->insert(
            [
                [
                    'name' => "1",
                    'description' => "Grade 1"
                ],
                [
                    'name' => "2",
                    'description' => "Grade 2"
                ],
                [
                    'name' => "3",
                    'description' => "Grade 3"
                ],
                [
                    'name' => "4",
                    'description' => "Grade 4"
                ],
                [
                    'name' => "5",
                    'description' => "Grade 5"
                ],
                [
                    'name' => "6",
                    'description' => "Grade 6"
                ],
                [
                    'name' => "7",
                    'description' => "Grade 7"
                ],
                [
                    'name' => "8",
                    'description' => "Grade 8"
                ],
                [
                    'name' => "9",
                    'description' => "Grade 9"
                ],
                [
                    'name' => "10",
                    'description' => "Grade 10"
                ],
                [
                    'name' => "11",
                    'description' => "Grade 11"
                ],
                [
                    'name' => "12",
                    'description' => "Grade 12"
                ],
                [
                    'name' => "13",
                    'description' => "Grade 13"
                ],
            ]
        );
    }
}
