<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * Error Create New User
     *
     * @return void
     */
    public function testErrorCreateNewUser()
    {
        $response = $this->postJson('/api/user');

        $response->assertStatus(422);
    }

    /**
     * Create New User
     *
     * @return void
     */
    public function testCreateNewUser()
    {
        $payload = [
            'name' => 'Marcus',
            'email' => 'marcus@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'bio' => 'Esta é a minha bio...',
            'profile_picture' => 'profile.jpg',
            'city' => 'Belém',
            'state' => 'Pará'
        ];

        $response = $this->postJson('/api/user', $payload, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(201);
    }
}
