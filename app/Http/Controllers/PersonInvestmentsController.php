<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvestmentResource;
use App\Http\Resources\PersonInvestmentsCollection;
use App\Models\Person;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class PersonInvestmentsController extends Controller
{

    public function index($person_id)
    {
        try {
            $person = Person::with('investments')->findOrFail($person_id);

            return response()->json(
                [
                    InvestmentResource::collection($person->investments()->paginate(5))->response()->getData(),
                ],
                Response::HTTP_OK,
            );

        } catch (ModelNotFoundException $m){
            return response()->json(
                [
                    'message' => 'Investment Not Found'
                ],
                Response::HTTP_NOT_FOUND,
            );
        }
    }
}
