<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_login()
    {
        $user = User::factory()->create();
        
        $response = $this->json('POST', '/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response
            ->assertJsonStructure(['data', 'message'])
            ->assertJson([
                'data' => [
                    'email'      => $user->email,
                    'name'       => $user->name,
                    'created_at' => $user->created_at->diffForHumans(),
                    'updated_at' => $user->updated_at->diffForHumans()
                ],
                'message' => 'Success'
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function incorrect_email()
    {
        User::factory()->create();

        $response = $this->json('POST', 'api/login', [
            'email'     => 'bad_email@gmail.com',
            'password'  => 'password'
        ]);

        $response
            ->assertJsonStructure(['errors', 'message'])
            ->assertJson([
                'errors'  => 'Incorrect login details',
                'message' => 'Unauthenticated'
            ])
            ->assertStatus(401);
    }

    /** @test */
    public function incorrect_password()
    {
        $user = User::factory()->create();

        $response = $this->json('POST', 'api/login', [
            'email'    => $user->email,
            'password' => '123456'
        ]);

        $response
            ->assertJsonStructure(['errors', 'message'])
            ->assertJson([
                'errors'  => 'Incorrect login details',
                'message' => 'Unauthenticated'
            ])
            ->assertStatus(401);
    }

    /** @test */
    public function can_logout()
    {
        $this->withExceptionHandling();
        
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->json('GET', 'api/logout');

        $response->assertStatus(204);
    }
}
