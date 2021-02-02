<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Task;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminControllerTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function admin_route_needs_authentication()
    {
        $response = $this->json('GET', 'api/admin/users');

        $response->assertStatus(401)->assertJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    public function admin_route_needs_authorization()
    {
        $employee = User::factory()->create();
        $employee->assignRole('employee');

        $response = $this->actingAs($employee)->json('GET', 'api/admin/users');

        $response->assertStatus(403)->assertJson(['message' => 'User does not have the right roles.']);
    }

    /** @test */
    public function admin_can_login_to_dashboard()
    {
        $response = $this->json('POST', 'api/admin-login', [
            'email' => 'admin@admin.com',
            'password' => 'admin'
        ]);

        $response
            ->assertJson([
                'data' => [
                    'user' => [
                        'email' => 'admin@admin.com'
                    ]
                ]
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function admin_login_is_protected_from_user_logging_in()
    {
        $user = User::find(8);

        $response = $this->json('POST', 'api/admin-login', [
            'email' => $user->email,
            'password' => 'admin'
        ]);

        $response
            ->assertJson([
                'message' => 'Unauthorized'
            ])
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_get_all_users()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->json('GET', 'api/admin/users');

        $response
            ->assertStatus(200)
            ->assertJsonCount(12, 'data.users');
    }

   /** @test */
   public function admin_can_create_new_user()
   {        
       $user = User::factory()->create();
       $user->assignRole('admin');

       $response = $this
           ->actingAs($user)
           ->json('POST', 'api/admin/users', [
               'name'                  => 'John',
               'email'                 => 'john@companyname.com',
               'password'              => 'testas123',
               'password_confirmation' => 'testas123'
           ]);

       $response
           ->assertJson([
               'data' => [
                   'name' => 'John',
                   'email' => 'john@companyname.com'
               ],
               'message' => 'New user created successfully'
           ])
           ->assertStatus(201);

       $this->assertDatabaseCount('users', 13);
   }

    /** @test */
    public function admin_can_update_user_details()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->json('PUT', 'api/admin/users/1', [
            'name'                  => 'John',
            'email'                 => 'john@email.com',
            'password'              => 'dasdasnbduiasbfuasfas',
            'password_confirmation' => 'dasdasnbduiasbfuasfas'
        ]);

        $user = User::find(1)->first();
        
        $response
            ->assertJson([
                'data' => [
                    'name'  => $user->name,
                    'email' => $user->email,
                ]
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->json('DELETE', 'api/admin/users/1');

        $response->assertStatus(204);

        $this->assertDatabaseCount('users', 11);
    }

    /** @test */
    public function admin_can_get_individual_user()
    {
        Task::factory(40)->create();
        $user = User::find(2);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->json('GET', 'api/admin/users/2');

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email
                ]
            ]);
    }

    /** @test */
    public function admin_can_get_all_tasks()
    {
        Task::factory(20)->create();
        
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->json('GET', 'api/admin/tasks');

        $response
            ->assertStatus(200)
            ->assertJsonCount(20, 'data.tasks');
    }

    /** @test */
    public function admin_can_see_task_by_id()
    {
        Task::factory(20)->create();
        
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->json('GET', 'api/admin/tasks/15');

        $task = Task::find(15);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id'      => $task->id,
                    'title'   => $task->title,
                    'content' => $task->content,
                    'status'  => $task->status,
                    'user'    => [
                        'name'  => $task->user->name,
                        'email' => $task->user->email
                    ]    
                ]
            ]);
    }

    /** @test */
    public function admin_can_create_task()
    {        
        $user = User::find(1);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->json('POST', 'api/admin/tasks', [
                    'title' => 'My task',
                    'content' => 'Task content',
                    'status' => 'to_do',
                    'user_id' => 1
                ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'My task',
                    'content' => 'Task content',
                    'status' => 'to_do',
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email
                    ]
                ]
            ]);

        $this->assertDatabaseCount('tasks', 1);
    }

    /** @test */
    public function admin_can_update_task()
    {   
        Task::factory()->create();

        $user = User::find(1);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->json('PUT', 'api/admin/tasks/1', [
                    'title' => 'My task',
                    'content' => 'Task content',
                    'status' => 'completed',
                    'user_id' => 1
                ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'My task',
                    'content' => 'Task content',
                    'status' => 'completed',
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email
                    ]
                ]
            ]);
    }

    /** @test */
    public function admin_can_delete_task()
    {
        Task::factory()->create();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->json('DELETE', 'api/admin/tasks/1');

        $response->assertStatus(204);
        $this->assertDatabaseCount('tasks', 0);
    }

    /** @test */
    public function return_404_if_user_does_not_exist()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->json('GET', 'api/admin/users/100');

        $response->assertStatus(404);
    }

    /** @test */
    public function return_404_if_task_does_not_exist()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->json('GET', 'api/admin/tasks/100');

        $response->assertStatus(404);
    }
}
