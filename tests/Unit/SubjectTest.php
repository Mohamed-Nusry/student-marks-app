<?php

namespace Tests\Unit;

use App\Models\Subject;
use App\Models\User;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function test_create_data()
    {

        $user = User::factory()->create([
            'name'  => 'admin100',
            'email'  => 'admin100@gmail.com',
            'password' => bcrypt($password = '12345678'),
            'role_id'  => 1,
        ]);

        $response = $this->actingAs($user)->post('/api/subject', [
            'name'  => 'subject100',
            'description'  => 'Test Subject',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_get_data()
    {

        $user = User::factory()->create([
            'name'  => 'admin101',
            'email'  => 'admin101@gmail.com',
            'password' => bcrypt($password = '12345678'),
            'role_id'  => 1,
        ]);

        $response = $this->actingAs($user)->get('/api/subject');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
        ]);
    }

    public function test_update_data()
    {
        $user = User::factory()->create([
            'name'  => 'admin102',
            'email'  => 'admin102@gmail.com',
            'password' => bcrypt($password = '12345678'),
            'role_id'  => 1,
        ]);

        $response = $this->actingAs($user)->put('/api/subject/1', [
            'name'  => 'subject105',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
        ]);

    }

    public function test_single_data()
    {
        $user = User::factory()->create([
            'name'  => 'admin103',
            'email'  => 'admin103@gmail.com',
            'password' => bcrypt($password = '12345678'),
            'role_id'  => 1,
        ]);

        $response = $this->actingAs($user)->get('/api/subject/1');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
        ]);

    }

    public function test_delete_data()
    {
        $user = User::factory()->create([
            'name'  => 'admin104',
            'email'  => 'admin104@gmail.com',
            'password' => bcrypt($password = '12345678'),
            'role_id'  => 1,
        ]);

        $response = $this->actingAs($user)->delete('/api/subject/1');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
        ]);

    }



}
