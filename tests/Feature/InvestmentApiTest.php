<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Investment;

use Carbon\Carbon;

use Illuminate\Contracts\Pagination\Paginator;

class InvestmentApiTest extends TestCase
{
    public function test_can_list_investments() {
        $user = User::factory()->create();

        $this
            ->actingAs($user, 'sanctum')
            ->get(route('investments.index'))
            ->assertStatus(200);
    }

    public function test_cant_list_investments_without_login() {
        $this
            ->get(route('investments.index'))
            ->assertStatus(500);
    }

    // This list should have pagination.
    public function test_list_investments_are_paginated() {
        $user = User::factory()->create();

        $response = $this
                        ->actingAs($user, 'sanctum')    
                        ->get(route('investments.index'))
                        ->assertJsonStructure([
                            'current_page',
                            'from',
                            'data',
                            'first_page_url',
                            'last_page',
                            'last_page_url',
                            'links',
                            'next_page_url',
                            'path',
                            'per_page',
                            'prev_page_url',
                            'to',
                            'total',
                        ]);
    }

    public function test_can_view_investment() {
        $user = User::factory()->create();
        $user->investments()->create([
            'amount' => '10.00',
            'inserted_at' => '2021-10-10'
        ]);

        $investment = Investment::orderBy('id', 'desc')->first();

        $this
            ->actingAs($user, 'sanctum')
            ->get(route('investments.show', ['investment' => $investment->id]))
            ->assertStatus(200);
    }

    public function test_cant_view_investment_without_login() {
        $user = User::factory()->create();
        $user->investments()->create([
            'amount' => '10.00',
            'inserted_at' => '2021-10-10'
        ]);

        $investment = Investment::orderBy('id', 'desc')->first();

        $this
            ->get(route('investments.show', ['investment' => $investment->id]))
            ->assertStatus(500);
    }

    public function test_cant_view_investment_that_doesnt_exist() {
        $user = User::factory()->create();
        $user->investments()->create([
            'amount' => '10.00',
            'inserted_at' => '2021-10-10'
        ]);

        $investment = Investment::orderBy('id', 'desc')->first();

        $this
            ->actingAs($user, 'sanctum')            
            ->get(route('investments.show', ['investment' => $investment->id + 1]))
            ->assertStatus(404);
    }
    
    public function test_can_withdrawal_investment() {
        $user = User::factory()->create();
        $user->investments()->create([
            'amount' => '10.00',
            'inserted_at' => '2021-10-10'
        ]);

        $investment = Investment::orderBy('id', 'desc')->first();

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('investments.withdrawal', ['id' => $investment->id]))
            ->assertStatus(201);
    }

    public function test_cant_view_withdrawal_without_login() {
        $user = User::factory()->create();
        $user->investments()->create([
            'amount' => '10.00',
            'inserted_at' => '2021-10-10'
        ]);

        $investment = Investment::orderBy('id', 'desc')->first();

        $this
            ->post(route('investments.withdrawal', ['id' => $investment->id]))
            ->assertStatus(500);
    }

    public function test_cant_withdrawal_investment_that_doesnt_exist() {
        $user = User::factory()->create();
        $user->investments()->create([
            'amount' => '10.00',
            'inserted_at' => '2021-10-10'
        ]);

        $investment = Investment::orderBy('id', 'desc')->first();

        $this
            ->actingAs($user, 'sanctum')            
            ->post(route('investments.withdrawal', ['id' => $investment->id + 1]))
            ->assertStatus(404);
    }

    public function test_investment_doesnt_exist_after_withdrawal() {
        $user = User::factory()->create();
        $user->investments()->create([
            'amount' => '10.00',
            'inserted_at' => '2021-10-10'
        ]);

        $investment = Investment::orderBy('id', 'desc')->first();

        $this
            ->actingAs($user, 'sanctum')            
            ->post(route('investments.withdrawal', ['id' => $investment->id]))
            ->assertStatus(201);
        
        $this->assertEquals(0, Investment::where('id', $investment->id)->count());
    }

    public function test_investment_exist_after_withdrawal_as_deleted() {
        $user = User::factory()->create();
        $user->investments()->create([
            'amount' => '10.00',
            'inserted_at' => '2021-10-10'
        ]);

        $investment = Investment::orderBy('id', 'desc')->first();

        $this
            ->actingAs($user, 'sanctum')            
            ->post(route('investments.withdrawal', ['id' => $investment->id]))
            ->assertStatus(201);

        $this->assertEquals(1, Investment::withTrashed()->where('id', $investment->id)->count());
    }

    public function test_can_create_investment() {
        $user = User::factory()->create();

        $data = [
            'amount' => '100.00',
            'inserted_at' => '2021-10-21'
        ];

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('investments.store'), $data)
            ->assertStatus(201);
    }

    public function test_can_create_investment_without_login() {
        $data = [
            'amount' => '100.00',
            'inserted_at' => '2021-10-21'
        ];

        $this
            ->post(route('investments.store'), $data)
            ->assertStatus(500);
    }

    public function test_cant_create_investment_without_amount() {
        $user = User::factory()->create();

        $data = [
            'inserted_at' => '2021-10-21'
        ];

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('investments.store'), $data)
            ->assertStatus(302);
    }

    public function test_cant_create_investment_with_non_numeric_amount() {
        $user = User::factory()->create();

        $data = [
            'amount' => 'Wow! This must fail.',
            'inserted_at' => '2021-10-21'
        ];

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('investments.store'), $data)
            ->assertStatus(302);
    }

    // An investment should not be or become negative.
    public function test_cant_create_investment_with_negative_amount() {
        $user = User::factory()->create();

        $data = [
            'amount' => '-1.5',
            'inserted_at' => '2021-10-21'
        ];

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('investments.store'), $data)
            ->assertStatus(302);
    }

    public function test_cant_create_investment_greater_than_max_range_amount() {
        $user = User::factory()->create();

        $data = [
            'amount' => '9999999.00',
            'inserted_at' => '2021-10-21'
        ];

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('investments.store'), $data)
            ->assertStatus(302);
    }

    public function test_cant_create_investment_more_than_two_decimal_places_amount() {
        $user = User::factory()->create();

        $data = [
            'amount' => '1050.0053',
            'inserted_at' => '2021-10-21'
        ];

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('investments.store'), $data)
            ->assertStatus(302);
    }

    public function test_cant_create_investment_with_comma_amount() {
        $user = User::factory()->create();

        $data = [
            'amount' => '1050,50',
            'inserted_at' => '2021-10-21'
        ];

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('investments.store'), $data)
            ->assertStatus(302);
    }

    public function test_cant_create_investment_without_inserted_at() {
        $user = User::factory()->create();

        $data = [
            'amount' => '1050.50'
        ];

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('investments.store'), $data)
            ->assertStatus(302);
    }

    public function test_cant_create_investment_with_wrong_format_inserted_at() {
        $user = User::factory()->create();

        $data = [
            'amount' => '1050.50',
            'inserted_at' => '05/10/2021'
        ];

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('investments.store'), $data)
            ->assertStatus(302);
    }

    // The creation date of an investment can be today or a date in the past.
    public function test_cant_create_investment_with_future_date_inserted_at() {
        $user = User::factory()->create();

        $currentDate = Carbon::now()->addDay()->format('Y-m-d');

        $data = [
            'amount' => '1050.50',
            'inserted_at' => $currentDate
        ];

        $this
            ->actingAs($user, 'sanctum')
            ->post(route('investments.store'), $data)
            ->assertStatus(302);
    }
}
