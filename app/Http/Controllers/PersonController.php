<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonInvestmentsResource;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index()
    {
        return PersonResource::collection(Person::all());
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $person = new Person($data);

        $saved = $person->save($data);

        if ($saved) {
            return new PersonResource($person);
        }
    }

    public function show(string $id)
    {
        $person = Person::find($id);

        if (! $person) {
            return response()->json([
                'data'  =>  [
                    'success'   =>  false,
                    'message'   =>  'Person not found'
                ]
            ], 404);
        }

        return new PersonResource($person);
    }

    public function investments(string $id)
    {
        $person = Person::find($id);

        if (! $person) {
            return response()->json([
                'data'  =>  [
                    'success'   =>  false,
                    'message'   =>  'Person not found'
                ]
            ], 404);
        }

        return new PersonInvestmentsResource($person->investments()->paginate(10));
    }
}
