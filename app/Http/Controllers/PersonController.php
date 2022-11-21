<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(
            [
                'data' => PersonResource::collection(Person::all()),
            ],
            Response::HTTP_OK,
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePersonRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePersonRequest $request)
    {
        try {

            $data = $request->validated();

            return response()->json(
                [
                    'data' => Person::create($data),
                    'message' => 'Person created!',
                ],
                Response::HTTP_CREATED,
            );

        } catch (\Exception $e){

            return response()->json(
                'Create person failed',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return PersonResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $person = Person::with('investments')->findOrFail($id);

            return response()->json(
                [
                    'data' => new PersonResource($person),
                ],
                Response::HTTP_OK,
            );

        } catch (ModelNotFoundException $m){

            return response()->json(
                'Person not found',
                Response::HTTP_NOT_FOUND,
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePersonRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePersonRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $person = Person::findOrFail($id);
            $person->update($data);

            return response()->json(
                [
                    'data' => $person,
                    'message' => 'Person updated'
                ],
                Response::HTTP_OK,
            );

        } catch (ModelNotFoundException $m){

            return response()->json(
                'Person not found',
                Response::HTTP_NOT_FOUND,
            );

        } catch (\Exception $e){
            return response()->json(
                'Update person failed',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try{
            $person = Person::findOrFail($id);

            $person->delete();

            return response()->json(
                [
                    'data' => $person,
                    'message' => 'Person deleted'
                ],
                Response::HTTP_OK,
            );

        } catch (ModelNotFoundException $m){

            return response()->json(
                'Person not found',
                Response::HTTP_NOT_FOUND,
            );

        } catch (\Exception $e){
            return response()->json(
                'Delete person failed',
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
