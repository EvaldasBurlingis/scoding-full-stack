<?php

namespace Tests\Feature\Validations;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTaskStatusRequestTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function verify_status_is_required()
    {
        Task::factory()->create([
            'id'      => 1,
            'title'   => 'Old title',
            'content' => 'Old content',
            'status'  => 'to_do',
            'user_id' => 1
        ]);

        $user = User::find(1);

        $response = $this
                ->actingAs($user)
                ->json('PATCH', 'api/users/1/tasks/1', [
                    'status'  => ''
                ]);
        
        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                'errors' => [
                    'status' => ['The status field is required.']
                ]
            ]);
    }

    /** @test */
    public function verify_status_is_in_one_of_allowed_strings()
    {
        Task::factory()->create([
            'id'      => 1,
            'title'   => 'Old title',
            'content' => 'Old content',
            'status'  => 'to_do',
            'user_id' => 1
        ]);

        $user = User::find(1);

        $response = $this
                ->actingAs($user)
                ->json('PATCH', 'api/users/1/tasks/1', [
                    'status'  => 'adasd'
                ]);
        
        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                'errors' => [
                    'status' => ['The selected status is invalid.']
                ]
            ]);
    }
}
