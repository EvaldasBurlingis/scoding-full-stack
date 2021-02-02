<?php

namespace Tests\Feature\Validations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function verify_requires_fields()
    {
        $response = $this->json('POST', 'api/login', [
            'email'     => '',
            'password'  => ''
        ]);

        $response
            ->assertJsonStructure(['errors', 'message'])
            ->assertJson([
                'errors' => [
                    'email' => ['Email is required'],
                    'password' => ['Password is required']
                ],
                'message' => 'The given data was invalid.'
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function verify_email_type()
    {
        $response = $this->json('POST', 'api/login', [
            'email'     => 'da@@dasd.com',
            'password'  => ''
        ]);

        $response
            ->assertJsonStructure(['errors', 'message'])
            ->assertJson([
                'errors' => [
                    'email' => ['Invalid email address'],
                    'password' => ['Password is required']
                ],
                'message' => 'The given data was invalid.'
            ])
            ->assertStatus(422);
    }
}
