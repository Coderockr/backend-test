<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use App\Http\Services\Response;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class InvestorController extends Controller
{
    use Response;

    public function __construct()
    {
        //
    }

    public function index()
    {
        return $this->responseSuccess('success', Investor::all());
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required | string',
            ]);

            $investor = Investor::create([
                'id'    =>  (string) Uuid::uuid4(),
                'name'  =>  $request->name,
            ]);
            return $this->responseSuccess('investor successfully created!', $investor);
        } catch (\Throwable $th) {
            // return $th;
            return $this->responseError(401, 'error', $th->getMessage());
        }
    }
}
