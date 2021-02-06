<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_correctly_works()
    {
        $payload = [
            'name' => 'Daniel Freitas',
            'email' => 'daniel@coderock.com.br',
            'password' => '1SafePassword*!!',
            'password_confirmation' => '1SafePassword*!!',
        ];

        $this->post(route('register'), $payload)
            ->assertStatus(201)
            ->assertJson([
                'name' => $payload['name'],
                'email' => $payload['email'],
            ])
            ->assertJsonStructure([
                'id', 'name', 'email', 'token', 'created_at', 'updated_at',
            ]);
    }

    public function test_confirmation_are_required()
    {
        $payload = [
            'name' => 'Daniel Freitas',
            'email' => 'daniel@coderock.com.br',
            'password' => '1SafePassword*!!',
        ];

        $this->post(route('register'), $payload)
            ->assertStatus(302)
            ->assertSessionHasErrors(['password',]);
    }

    public function test_required_fields_are_blocking()
    {
        $this->post(route('register'))
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'name', 'email', 'password',
            ]);
    }

    public function test_unique_email_are_blocking()
    {
        User::factory()->create(['email' => 'daniel@coderock.com.br']);

        $payload = [
            'name' => 'Daniel Freitas',
            'email' => 'daniel@coderock.com.br',
            'password' => '1SafePassword*!!',
        ];

        $this->post(route('register'), $payload)
            ->assertStatus(302)
            ->assertSessionHasErrors(['email',]);
    }

    public function test_email_format_are_blocking()
    {
        $payload = [
            'name' => 'Daniel Freitas',
            'email' => 'An invalid email',
            'password' => '1SafePassword*!!',
        ];

        $this->post(route('register'), $payload)
            ->assertStatus(302)
            ->assertSessionHasErrors(['email',]);
    }

    public function test_min_length_are_blocking()
    {
        $payload = [
            'name' => 'ABC',
            'email' => 'daniel@coderock.com.br',
            'password' => '1234567',
        ];

        $this->post(route('register'), $payload)
            ->assertStatus(302)
            ->assertSessionHasErrors(['name', 'password',]);
    }
}
