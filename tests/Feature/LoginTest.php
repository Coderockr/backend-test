<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_correctly_works()
    {
        $user = User::factory()->create(['password' => bcrypt('1SafePassword*!!'),]);
        $payload = [
            'email' => $user->email,
            'password' => '1SafePassword*!!',
        ];

        $this->post(route('login'), $payload)
            ->assertStatus(200)
            ->assertJson([
                'name' => $user->name,
                'email' => $user->email,
            ])
            ->assertJsonStructure([
                'id', 'name', 'email', 'token', 'created_at', 'updated_at',
            ]);
    }

    public function test_required_fields_are_blocking()
    {
        $this->post(route('register'))
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'email', 'password',
            ]);
    }

    public function test_wrong_credentials_are_blocking()
    {
        $payload = [
            'email' => 'wrong@mail.com',
            'password' => '1WrongButSafePassword*!!',
        ];

        $this->post(route('login'), $payload)->assertStatus(401);
    }
}
