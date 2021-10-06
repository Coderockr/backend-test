<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;

class InvestmentAPITest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateInvestmentPastDate()
    {
        $token = $this->JWTtoken('admin');

        $this->json(
            'POST',
            '/api/v1/investment/create',
            [
                'owner'  => '1',
                'date'   => date('Y-m-d', strtotime('-60 days')),
                'amount' => '200.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )
        ->seeStatusCode(200)
        ->seeJson(
            [
                'status' => 'success',
            ]
        );
    }

    public function testCreateInvestmentCurrentDate()
    {
        $token = $this->JWTtoken('admin');
        $this->json(
                'POST',
                '/api/v1/investment/create',
                [
                    'owner'  => '1',
                    'date'   => date('Y-m-d'),
                    'amount' => '200.00'
                ],
                [
                    'HTTP_Authorization' => $token
                ]
            )
            ->seeStatusCode(200)
            ->seeJson(
                [
                    'status' => 'success',
                ]
            );
    }

    public function testCreateInvestmentCustomerUser()
    {
        $token = $this->JWTtoken('customer');
        $this->json(
                'POST',
                '/api/v1/investment/create',
                [
                    'owner'  => '2',
                    'date'   => date('Y-m-d'),
                    'amount' => '200.00'
                ],
                [
                    'HTTP_Authorization' => $token
                ]
            )
            ->seeStatusCode(200)
            ->seeJson(
                [
                    'status' => 'success',
                ]
            );
    }


    public function testIfInvestmentCreationAlertsWhenWrongTypeIsPastToOwner()
    {
        $token = $this->JWTtoken('admin');
        $this->json(
            'POST',
            '/api/v1/investment/create',
            [
                'owner'  => 'T',
                'date'   => '2020-01-01',
                'amount' => '200.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(422);
    }

    public function testIfInvestmentCreationAlertsWhenWrongTypeIsPastToDate()
    {
        $token = $this->JWTtoken('admin');
        $this->json(
            'POST',
            '/api/v1/investment/create',
            [
                'owner'  => '1',
                'date'   => 200101,
                'amount' => '200.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(422);
    }

    public function testIfInvestmentCreationAlertsWhenWrongFormatoIsPastToAmount()
    {
        $token = $this->JWTtoken('admin');
        $this->json(
            'POST',
            '/api/v1/investment/create',
            [
                'owner'  => '1',
                'date'   => '2021-01-01',
                'amount' => '20000'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(422);
    }

    public function testIfInvestmentCreationAlertsWhenWrongFormatoIsPastToAmountUsingComma()
    {
        $token = $this->JWTtoken('admin');
        $this->json(
            'POST',
            '/api/v1/investment/create',
            [
                'owner'  => '1',
                'date'   => '2021-01-01',
                'amount' => '200,00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(422);
    }


    public function testIfInvestmentCreationAlertsWhenDateIsInTheFuture()
    {
        $token = $this->JWTtoken('admin');
        $this->json(
            'POST',
            '/api/v1/investment/create',
            [
                'owner'  => '1',
                'date'   => date('Y-m-d', strtotime('+10 days')),
                'amount' => '200,00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(422);
    }


    public function testIfInvestmentCreationAlertsWhenTheInvestmentIsEqualToZero()
    {
        $token = $this->JWTtoken('admin');
        $this->json(
            'POST',
            '/api/v1/investment/create',
            [
                'owner'  => '1',
                'date'   => date('Y-m-d'),
                'amount' => '0'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(422);
    }


    public function testIfInvestmentCreationAlertsWhenTheInvestmentIsLowerThenZero()
    {
        $token = $this->JWTtoken('admin');
        $this->json(
            'POST',
            '/api/v1/investment/create',
            [
                'owner'  => '1',
                'date'   => date('Y-m-d'),
                'amount' => '1-'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(422);
    }

    public function testIfCreationFailsOnCreationWithDifferentCustomerId()
    {
        $adminRoleId = 1;

        $adminUserID = (User::select('users_id')
            ->join('permissions', 'users.id', '=', 'permissions.users_id')
            ->where('permissions.roles_id', '=', $adminRoleId)
            ->limit(1)
            ->get()
            ->toArray())[0];

        $token = $this->JWTtoken('customer');

        $this->json(
                'POST',
                '/api/v1/investment/create',
                [
                    'owner'  => $adminUserID['users_id'],
                    'date'   => date('Y-m-d'),
                    'amount' => '200.00'
                ],
                [
                    'HTTP_Authorization' => $token
                ]
            )
            ->seeStatusCode(401);
    }


    private function JWTtoken($role)
    {
        $this->post(
            '/auth/login',
            [
                'email' => $role . '@email.com',
                'password'=> 'passwd123'
            ]
        );

        $r = json_decode($this->response->getContent());

        if ($r->access_token) {
            return  $r->access_token;
        }
    }
}
