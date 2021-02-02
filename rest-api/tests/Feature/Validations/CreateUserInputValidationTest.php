<?php

namespace Tests\Feature\Validations;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserInputValidationTest extends TestCase
{

    use RefreshDatabase;
    /** @test */
    public function verify_create_user_required_field()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->json('POST', 'api/admin/users', [
                'name'                  => '',
                'email'                 => '',
                'password'              => '',
                'password_confirmation' => ''
            ]);
        
        $response
            ->assertJsonStructure(['errors', 'message'])
            ->assertJson([
                'errors' => [
                    'name' => ['Name is required.'],
                    'email' => ['Email is required.'],
                    'password' => ['Password is required.']
                ],
                'message' => 'The given data was invalid.'
            ])    
            ->assertStatus(422);
    }

    /** @test */
    public function verify_create_user_email_type()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->json('POST', 'api/admin/users', [
                'name'                  => 'John',
                'email'                 => 'john',
                'password'              => 'testas123',
                'password_confirmation' => 'testas123'
            ]);
        
        $response
            ->assertJsonStructure(['errors', 'message'])
            ->assertJson([
                'errors' => [
                    'email' => ['Invalid email address.']
                ],
                'message' => 'The given data was invalid.'
            ])    
            ->assertStatus(422);
    }

    /** @test */
    public function verify_create_user_email_is_unique()
    {
        $user = User::factory()->create(['email' => 'test@test.com']);
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->json('POST', 'api/admin/users', [
                'name'                  => 'John',
                'email'                 => 'test@test.com',
                'password'              => 'testas123',
                'password_confirmation' => 'testas123'
            ]);
        
        $response
            ->assertJsonStructure(['errors', 'message'])
            ->assertJson([
                'errors' => [
                    'email' => ['Email is already registered.']
                ],
                'message' => 'The given data was invalid.'
            ])    
            ->assertStatus(422);
    }

    /** @test */
    public function verify_create_user_password_confirmation()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->json('POST', 'api/admin/users', [
                'name'                  => 'John',
                'email'                 => 'john@email.com',
                'password'              => 'testas123',
                'password_confirmation' => 'testas1233'
            ]);
        
        $response
            ->assertJsonStructure(['errors', 'message'])
            ->assertJson([
                'errors' => [
                    'password' => ['Password confirmation does not match.']
                ],
                'message' => 'The given data was invalid.'
            ])    
            ->assertStatus(422);
    }

        /** @test */
        public function verify_create_user_password_min_length()
        {
            $user = User::factory()->create();
            $user->assignRole('admin');

            $response = $this
                ->actingAs($user)
                ->json('POST', 'api/admin/users', [
                    'name'                  => 'John',
                    'email'                 => 'john@email.com',
                    'password'              => 'test',
                    'password_confirmation' => 'test'
                ]);
            
            $response
                ->assertJsonStructure(['errors', 'message'])
                ->assertJson([
                    'errors' => [
                        'password' => ['Password must be between 8-32 characters.']
                    ],
                    'message' => 'The given data was invalid.'
                ])    
                ->assertStatus(422);
        }

    /** @test */
    public function verify_create_user_password_max_length()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->json('POST', 'api/admin/users', [
                'name'                  => 'John',
                'email'                 => 'john@email.com',
                'password'              => 'dasdasnbduiasbfuasfasf1as4fsa46fs8a4f6sa1f86as4f6sa16f4asf',
                'password_confirmation' => 'dasdasnbduiasbfuasfasf1as4fsa46fs8a4f6sa1f86as4f6sa16f4asf'
            ]);
        
        $response
            ->assertJsonStructure(['errors', 'message'])
            ->assertJson([
                'errors' => [
                    'password' => ['Password must be between 8-32 characters.']
                ],
                'message' => 'The given data was invalid.'
            ])    
            ->assertStatus(422);
    }
}
