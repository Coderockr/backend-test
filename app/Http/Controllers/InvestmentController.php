<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Http\Services\Response;
use App\Models\Investor;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use App\Http\Services\GainCalculation;

class InvestmentController extends Controller
{
    use Response;

    public function __construct()
    {
        //
    }

    public function index($investor_id)
    {
        return $this->responseSuccess('success', Investment::where('investor_id', $investor_id)->get());
    }

    public function store(Request $request, $investor_id)
    {
        try {
            $this->validate($request, [
                'investment_date'   =>  'required | date_format:Y/m/d'
            ]);
        } catch (\Throwable $th) {
            return $this->responseError(401, 'parameters are missing!', $th);
        }

        try {
            $investment = Investment::create([
                'id'                            =>  (string) Uuid::uuid4(),
                'investor_id'                   =>  $investor_id,
                'initial_investment'            =>  0,
                'current_investment_value'      =>  0,
                'investment_reference_date'     =>  new Carbon($request->investment_date),
            ]);

            return $this->responseSuccess('investment successfully created!', $investment);
        } catch (\Throwable $th) {
            // return $th;
            return $this->responseError(401, 'an error has occurred!', $th);
        }
    }

    public function makeInvestment(Request $request, $investor_id, $investment_id)
    {
        try {
            $this->validate($request, [
                'amount'            =>  'required | numeric | gte:0',
            ]);
        } catch (\Throwable $th) {
            return $this->responseError(401, 'parameters are missing!', $th->getMessage());
        }

        try {
            $investment = Investment::where([
                ['id', '=', $investment_id],
                ['investor_id', '=', $investor_id]
            ])->first();
            if (!isset($investment)) throw new \Exception("Investment not found!", 1);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->responseError(401, $th->getMessage());
        }

        try {
            $investment->initial_investment = (float)$investment->initial_investment;

            if ($investment->initial_investment == 0) {
                $investment->initial_investment         =   $request->amount;
                // $investment->current_investment_value   =   $request->amount;
            };

            // $investment->current_investment_value = (float) $gain_calculation->updateAmount($investment->current_investment_value);
            $investment->investment_reference_date = Carbon::now();

            $investment->save();
            return $this->responseSuccess('Successful investment!', $investment);
        } catch (\Throwable $th) {
            return $this->responseError(401, $th->getMessage());
        }
    }

    public function see($investor_id, $investment_id)
    {

        try {
            $investment = Investment::where([
                ['id', '=', $investment_id],
                ['investor_id', '=', $investor_id]
            ])->first();
            if (!isset($investment)) throw new \Exception("Investment not found!", 1);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->responseError(401, $th->getMessage());
        }

        try {

            $gainCalculation = new GainCalculation($investment->investment_reference_date);
            $valueWithEarnings = $gainCalculation->updateAmount($investment->initial_investment);

            return $this->responseSuccess(
                'success',
                [
                    'initial_investment'                =>  $investment->initial_investment,
                    'current_investment_value'          =>  $valueWithEarnings,
                ],
            );
        } catch (\Throwable $th) {
            //throw $th;
            return $this->responseError(401, 'error', $th->getMessage());
        }
    }

    public function withdrawInvestment(Request $request, $investor_id, $investment_id)
    {
        try {
            $this->validate($request, [
                'withdrawalValue'           =>  'required | numeric | gte:0',
                'date'                      =>  'required | date_format:Y/m/d'
            ]);
        } catch (\Throwable $th) {
            return $this->responseError(401, 'parameters are missing!', $th->getMessage());
        }

        try {
            $investment = Investment::where([
                ['id', '=', $investment_id],
                ['investor_id', '=', $investor_id]
            ])->first();
            if (!isset($investment)) throw new \Exception("Investment not found!", 1);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->responseError(401, $th->getMessage());
        }

        try {

            $gainCalculation = new GainCalculation($investment->investment_reference_date);
            $valueWithEarnings = $gainCalculation->updateAmount($investment->initial_investment);

            if ($request->withdrawalValue > $valueWithEarnings)
                throw new \Exception("The withdrawal amount has to be equal to the amount in the wallet!", 1);
        } catch (\Throwable $th) {
            return $this->responseError(401, $th->getMessage());
        }


        try {

            $investment->initial_investment = 0;
            $investment->investment_reference_date = Carbon::now();
            $investment->save();

            return $this->responseSuccess('investment successfully withdrawn!', $investment);
        } catch (\Throwable $th) {
            return $this->responseError(401, $th->getMessage());
        }
    }
}
