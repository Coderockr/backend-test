<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_user() {
        $data = [
            'name' => 'Joãozinho',
            'email' => 'joaoferreira@gmail.com',
            'password' => 'pikachu$5',
            'password_confirmation' => 'pikachu$5'
        ];

        $this
            ->post(route('users.register'), $data)
            ->assertStatus(201);
    }

    public function test_cant_register_user_without_name() {
        $data = [
            'email' => 'joaoferreira@gmail.com',
            'password' => 'pikachu$5',
            'password_confirmation' => 'pikachu$5'
        ];

        $this
            ->post(route('users.register'), $data)
            ->assertStatus(302);
    }

    public function test_cant_register_user_without_email() {
        $data = [
            'name' => 'Joãozinho',
            'password' => 'pikachu$5',
            'password_confirmation' => 'pikachu$5'
        ];

        $this
            ->post(route('users.register'), $data)
            ->assertStatus(302);
    }

    public function test_cant_register_user_without_password() {
        $data = [
            'name' => 'Joãozinho',
            'email' => 'joaoferreira@gmail.com',
            'password_confirmation' => 'pikachu$5'
        ];

        $this
            ->post(route('users.register'), $data)
            ->assertStatus(302);
    }

    public function test_cant_register_user_without_password_confirmation() {
        $data = [
            'name' => 'Joãozinho',
            'email' => 'joaoferreira@gmail.com',
            'password' => 'pikachu$5'
        ];

        $this
            ->post(route('users.register'), $data)
            ->assertStatus(302);
    }

    public function test_cant_register_user_with_different_password_and_password_confirmation() {
        $data = [
            'name' => 'Joãozinho',
            'email' => 'joaoferreira@gmail.com',
            'password' => 'pikachu$5',
            'password_confirmation' => '$5pikachu'
        ];

        $this
            ->post(route('users.register'), $data)
            ->assertStatus(302);
    }

    public function test_cant_register_user_with_not_unique_email() {
        User::create([
            'name' => 'Joãozinho 1',
            'email' => 'joaoferreira@gmail.com',
            'password' => 'pikachu$5',
            'password_confirmation' => 'pikachu$5'            
        ]);

        $data = [
            'name' => 'Joãozinho 2',
            'email' => 'joaoferreira@gmail.com',
            'password' => 'pikachu$55',
            'password_confirmation' => 'pikachu$55'
        ];

        $this
            ->post(route('users.register'), $data)
            ->assertStatus(302);
    }

    public function test_can_user_login() {
        User::create([
            'name' => 'Joãozinho 1',
            'email' => 'joaoferreira@gmail.com',
            'password' => bcrypt('pikachu$5'),
            'password_confirmation' => 'pikachu$5'            
        ]);

        $loginData = [
            'email' => 'joaoferreira@gmail.com',
            'password' => 'pikachu$5'
        ];

        $this
            ->post(route('users.login'), $loginData)
            ->assertStatus(201);
    }

    public function test_cant_user_login_without_email() {
        User::create([
            'name' => 'Joãozinho 1',
            'email' => 'joaoferreira@gmail.com',
            'password' => bcrypt('pikachu$5'),
            'password_confirmation' => bcrypt('pikachu$5'),           
        ]);

        $loginData = [
            'password' => 'pikachu$5'
        ];

        $this
            ->post(route('users.login'), $loginData)
            ->assertStatus(302);
    }   

    public function test_cant_user_login_without_password() {
        User::create([
            'name' => 'Joãozinho 1',
            'email' => 'joaoferreira@gmail.com',
            'password' => bcrypt('pikachu$5'),
            'password_confirmation' => bcrypt('pikachu$5'),          
        ]);

        $loginData = [
            'email' => 'joaoferreira@gmail.com'
        ];

        $this
            ->post(route('users.login'), $loginData)
            ->assertStatus(302);
    }  

    public function test_cant_user_login_with_wrong_credentials() {
        User::create([
            'name' => 'Joãozinho 1',
            'email' => 'joaoferreira@gmail.com',
            'password' => bcrypt('pikachu$5'),
            'password_confirmation' => bcrypt('pikachu$5'),           
        ]);

        $loginData = [
            'email' => 'joaoferreira@gmail.com',
            'password' => 'pikachu$1111'
        ];

        $this
            ->post(route('users.login'), $loginData)
            ->assertStatus(401);
    }  

    public function test_can_user_logout() {
        $user = User::create([
            'name' => 'Joãozinho 1',
            'email' => 'joaoferreira@gmail.com',
            'password' => bcrypt('pikachu$5'),
            'password_confirmation' => bcrypt('pikachu$5'),           
        ]);

        $token = $user->createToken('backend-test-testing-token')->plainTextToken;

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('users.logout'))
            ->assertStatus(200);
    } 
    
    public function test_can_user_logout_unauthenticated() {
        $user = User::create([
            'name' => 'Joãozinho 1',
            'email' => 'joaoferreira@gmail.com',
            'password' => bcrypt('pikachu$5'),
            'password_confirmation' => bcrypt('pikachu$5'),           
        ]);

        $token = $user->createToken('backend-test-testing-token')->plainTextToken;

        $this
            ->post(route('users.logout'))
            ->assertStatus(500);
    }
}
