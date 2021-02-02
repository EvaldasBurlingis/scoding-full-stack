<?php

namespace Tests\Feature\Validations;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTaskRequestTest extends TestCase
{
    use RefreshDatabase;

        /** @test */
        public function verify_required_fields()
        {        
            $admin = User::factory()->create();
            $admin->assignRole('admin');
    
            $response = $this
                ->actingAs($admin)
                ->json('POST', 'api/admin/tasks', [
                        'title' => '',
                        'content' => '',
                        'status' => '',
                        'user_id' => ''
                    ]);
    
            $response
                ->assertStatus(422)
                ->assertJson([
                    'errors' => [
                        'title' => ['The title field is required.'],
                        'user_id' => ['The user id field is required.'],
                        'status'   => ['The status field is required.']
                    ],
                    'message' => 'The given data was invalid.'
                ]);
        }

    /** @test */
    public function verify_user_exists()
    {        
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->json('POST', 'api/admin/tasks', [
                    'title' => 'dadsa',
                    'content' => 'dasd',
                    'status' => 'to_do',
                    'user_id' => 80
                ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'user_id' => ['The selected user id is invalid.'],
                ],
                'message' => 'The given data was invalid.'
            ]);
    }

    /** @test */
    public function verify_invalid_status()
    {        
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->json('POST', 'api/admin/tasks', [
                    'title' => 'dadsa',
                    'content' => 'dasd',
                    'status' => 'to_dsso',
                    'user_id' => 1
                ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'status' => ['The selected status is invalid.'],
                ],
                'message' => 'The given data was invalid.'
            ]);
    }

    /** @test */
    public function verify_valid_status_to_do()
    {        
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->json('POST', 'api/admin/tasks', [
                    'title' => 'dadsa',
                    'content' => 'dasd',
                    'status' => 'to_do',
                    'user_id' => 1
                ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function verify_valid_status_in_progress()
    {        
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->json('POST', 'api/admin/tasks', [
                    'title' => 'dadsa',
                    'content' => 'dasd',
                    'status' => 'in_progress',
                    'user_id' => 1
                ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function verify_valid_status_completed()
    {        
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->json('POST', 'api/admin/tasks', [
                    'title' => 'dadsa',
                    'content' => 'dasd',
                    'status' => 'in_progress',
                    'user_id' => 1
                ]);

        $response->assertStatus(201);
    }
}
