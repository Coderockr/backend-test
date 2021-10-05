<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Investments;
use App\Models\User;
use App\Models\Withdrawal;
use Auth;
use Carbon\Carbon;
use Investment\InvestmentCalculator;
use Investment\Investment;
use Investment\Withdrawal as InvestmentWithdrawl;
use Investment\Tax\Tax;

class InvestmentController extends Controller
{

    public function create(Request $request)
    { // owner, date, amount

        $this->validate(
            $request,
            [
                'owner'  => 'required|integer|gt:0',
                'date'   => 'required|date|date_format:Y-m-d|before_or_equal:today',
                'amount' => 'required|string|regex:#^\d+\.\d{1,2}#',
            ]
        );

        $this->allowed($request->owner);

        $regId = Investments::create(
            [
                'users_id' => $request->owner,
                'amount'   => transfomrAmountToInt($request->amount),
                'date'     => $request->date,
            ]
        );

        if ($regId) {
            return [
                'status' => "success",
                'data' => [
                    'investment' => $regId
                ]
            ];
        }

        return response()->json(
            [
                'status' => "error",
                'data' => []
            ],
            422
        );
    }

    public function withdrawal(Request $request)
    {
        $this->validate(
            $request,
            [
                'investmentId' => 'required|integer|gt:0',
                'type'         => ['required', 'string', 'regex:#^(partial|total)$#'],
                'date'         => 'required|date|date_format:Y-m-d|before_or_equal:today',
                'amount'       => ['required', 'string', 'regex:#^\d+\.\d{1,2}?$#'],
            ]
        );

        $investment = Investments::find($request->investmentId);
        $requestAmount = transfomrAmountToInt($request->amount);

        /*
            fix valor do amount q está diretão
            e corrigir o motivo de ficar deduzindo infinito
            Ajustar o retorno das tax
            Add o alerta
            Cabô! ^^
        */

        if (
            empty($investment)
            || $investment->status == 'closed'
            || \DateTime::createFromFormat('Y-m-d', $request->date) < \DateTime::createFromFormat('Y-m-d', $investment->date)
        ) {
            abort(
                401,
                'Action not allowed for this investment'
            );
        }

        $withdrawals = $this->fetchWithdrawal($investment->id);

        if ($this->hasPreviousWithdrawals($withdrawals, $request->date)) {
            abort(
                401,
                'Action not allowed! This investment has previous withdrawals'
            );
        }

        $investmentCalc = $this->calcInvestmentBalance(
            $investment->date,
            $investment->amount,
            $request['date'],
            array_merge(
                [$this->createWithdrawal(['date' => $request->date, 'amount' => $requestAmount])],
                array_map(
                    [$this, 'createWithdrawal'],
                    $withdrawals
                )
            ),
        );

        // vejo se tem saldo
        $balance = $investmentCalc->balance();

        if ($request->type == 'total') {
            $requestAmount = $balance;
        }

        ////Should not be able to withdraw more than the available balance
        if (($balance + $requestAmount) < $requestAmount) {
            abort(
                401,
                'Withdrawal amount exceeds available balance [' . formatFloat($balance) .'] '
            );
        }
        if ($balance >= $requestAmount) {
            // send msg to customer
            Withdrawal::create(
                [
                    'investment_id' => $request->investmentId,
                    'amount'        => $requestAmount,
                    'date'          => $request->date
                ]
            );

            if ($balance == $requestAmount) {
                // send msg to customer
                $investment->status = "closed";
                $investment->save();
            }
        }

        $taxes = Tax::calculate(
            $investment->date,
            $request['date'],
            $investment->amount,
            $investmentCalc->getGainAmount(),
            $balance,
            $requestAmount
        );

        return [
            'status' => "success",
            'data' => [
                'date'                    => $request['date'],
                'balance'                 => formatFloat(transfomrAmountTofloat($balance)),
                'withdrawal'              => $requestAmount,
                'InvestmentCurrentStatus' => $investment->status,
                'InvestmentCreation'      => $investment->date,
                'tax'                     => $taxes['taxAmountToPay']
            ]
        ];
    }

    public function hasPreviousWithdrawals($withdrawals, $date)
    {
        if(is_array($withdrawals)) {
            foreach ($withdrawals as $withdrawal) {
                if (Carbon::create($date)->lt(Carbon::create($withdrawal['date']))) {
                    return true;
                }
            }
        }
        return false;
    }

    public function view(Request $request)
    {
        $this->validate(
            $request,
            ['investmentId'  => 'required|integer|gt:0']
        );

        $investment = Investments::find($request->investmentId);

        if (empty($investment)) {
            abort(
                404,
                'Investment not found'
            );
        }

        $withdrawals = $this->fetchWithdrawal($investment->id);

        $investmentCalc = $this->calcInvestmentBalance(
            $investment->date,
            $investment->amount,
            date('Y-m-d'),
            array_map(
                [$this, 'createWithdrawal'],
                $withdrawals
            )
        );

        // vejo se tem saldo
        $balance = $investmentCalc->balance();

        return [
            'investment' => [
                'creationDate' => $investment->date,
                'creationAmount' => formatFloat(floatval($investment->amount))
            ],
            'balance'     => formatFloat($balance),
            'withdrawals' => $withdrawals,
        ];
    }

    public function fetchWithdrawal($investmentId)
    {
        return  Withdrawal::select('*')
        ->where('investment_id', '=', $investmentId)
        ->get()
        ->toArray();
    }

    public function listAdmin(Request $request)
    {
        $this->validate(
            $request,
            ['owner'  => 'required|integer|gt:0']
        );

        return $this->list($request->owner);
    }

    public function list($userId)
    {
        $users = Investments::select('*')
            ->where('users_id', '=', $userId)
            ->paginate(10);

        return $users;
    }

    public function calcInvestmentBalance($investmentDate, $investmentAmount, $requestDate, $withdrawals)
    {
        return new Investment(
            $investmentDate,
            $investmentAmount,
            $requestDate,
            $withdrawals
        );
    }

    public function createWithdrawal($withdral)
    {
        return InvestmentWithdrawl::createWithdrawal($withdral['date'], $withdral['amount']);
    }

    public function allowed($userId)
    {
        if (empty(Gate::allows('user-has-permission', $userId))) {
            abort(
                401,
                'Action not allowed'
            );
        }
    }
}
