<?php

namespace Tests\Unit;

use App\Models\Teacher;
use App\Models\User;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function test_create_data()
    {
        $response = $this->post('/api/teacher', [
            'first_name'  => 'Rowan',
            'last_name'  => 'William',
            'name'  => 'rowan16',
            'password'  => '12345678',
            'email'  => 'rowan16@gmail.com',
            'dob'  => '1985-08-21',
            'qualification'  => 'Bsc in Science',
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
            'name'  => 'harry17',
            'email'  => 'harry17@gmail.com',
            'password' => bcrypt($password = '12345678'),
            'role_id'  => 2,
        ]);

        $teacher = Teacher::create([
            'user_id'  => $user->id,
            'first_name'  => 'Harry',
            'last_name'  => 'Fernando',
            'email'  => $user->email,
            'dob'  => '1985-08-21',
            'qualification'  => 'Bsc in Science',
            'class_id'  => 1,
        ]);

        $response = $this->actingAs($user)->get('/api/teacher');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
        ]);
    }

    public function test_update_data()
    {
        $user = User::factory()->create([
            'name'  => 'sam17',
            'email'  => 'sam17@gmail.com',
            'password' => bcrypt($password = '12345678'),
            'role_id'  => 2,
        ]);

        $teacher = Teacher::create([
            'user_id'  => $user->id,
            'first_name'  => 'Sam',
            'last_name'  => 'Perera',
            'email'  => $user->email,
            'dob'  => '1985-08-21',
            'qualification'  => 'Bsc in Science',
            'class_id'  => 1,
        ]);

        $response = $this->actingAs($user)->put('/api/teacher/'.$user->id, [
            'first_name'  => 'Samson',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
        ]);

    }


}
