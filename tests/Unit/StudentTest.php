<?php

namespace Tests\Unit;

use App\Models\Student;
use App\Models\User;
use Tests\TestCase;

class StudentTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function test_create_data()
    {
        $response = $this->post('/api/student', [
            'first_name'  => 'Kate',
            'last_name'  => 'Olivia',
            'name'  => 'kate16',
            'password'  => '12345678',
            'email'  => 'kate16@gmail.com',
            'class_id'  => 1,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_get_data()
    {

        $user = User::factory()->create([
            'name'  => 'alex17',
            'email'  => 'alex17@gmail.com',
            'password' => bcrypt($password = '12345678'),
            'role_id'  => 3,
        ]);

        $student = Student::create([
            'user_id'  => $user->id,
            'first_name'  => 'Alex',
            'last_name'  => 'Zen',
            'email'  => $user->email,
            'class_id'  => 1,
        ]);

        $response = $this->actingAs($user)->get('/api/student');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
        ]);
    }

    public function test_update_data()
    {
        $user = User::factory()->create([
            'name'  => 'tory17',
            'email'  => 'tory17@gmail.com',
            'password' => bcrypt($password = '12345678'),
            'role_id'  => 3,
        ]);

        $student = Student::create([
            'user_id'  => $user->id,
            'first_name'  => 'Tory',
            'last_name'  => 'Roy',
            'email'  => $user->email,
            'class_id'  => 1,
        ]);

        $response = $this->actingAs($user)->put('/api/student/'.$user->id, [
            'first_name'  => 'Tori',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
        ]);

    }


}
