<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_task_route_needs_authentication()
    {
        $response = $this->json('GET', 'api/users/8/tasks');

        $response
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    public function user_can_see_his_tasks()
    {
        $this->withoutExceptionHandling();
        Task::factory(40)->create();

        $user = User::find(2);
        
        $response = $this->actingAs($user)->json('GET', 'api/users/2/tasks');

        $response
            ->assertStatus(200)
            ->assertJsonCount(count($user->tasks), 'data.tasks');
    }

    /** @test */
    public function user_cannot_see_other_user_tasks()
    {
        Task::factory(40)->create();

        $user = User::find(4);
        
        $response = $this->actingAs($user)->json('GET', 'api/users/2/tasks');

        $response
            ->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized']);
    }
    /** @test */
    public function user_can_change_task_status()
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
                    'status'  => 'completed'
                ]);
        
        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Task status updated successfully']);

        $this->assertDatabaseHas('tasks', [
            'id'      => 1,
            'title'   => 'Old title',
            'content' => 'Old content',
            'status'  => 'completed',
            'user_id' => 1
        ]);
    }
}
