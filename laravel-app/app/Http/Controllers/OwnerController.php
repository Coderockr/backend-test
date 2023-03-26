<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Investment;
use Illuminate\Http\Request;
use App\Http\Requests\OwnerStoreRequest;
use Symfony\Component\HttpFoundation\Response;

class OwnerController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Owners"},
     *     path="/api/owners",
     *     summary="Returns all owners",
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     *
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $owners = Owner::with('investments')
        ->paginate(10);

        return response()->json($owners);
    }

    /**
     * @OA\Get(
     *     tags={"Owners"},
     *     path="/api/owners/{id}",
     *     summary="Returns information about the owner and his investments",
     *     @OA\Parameter(
     *         description="Owner uuid",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="uuid", value="98c660e0-90b4-4ddf-8543-2183380d1a10", summary="An UUID value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     *
     * @param Owner $owner
     * @return \Illuminate\Http\Response
     */
    public function show(Owner $owner)
    {
      $owner->investments;
      return response()->json($owner);
    }

    /**
     * @OA\Get(
     *     tags={"Owners"},
     *     path="/api/owners/only-investments/{id}",
     *     summary="Returns all investments of an owner",
     *     @OA\Parameter(
     *         description="Owner uuid",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="uuid", value="98c660e0-90b4-4ddf-8543-2183380d1a10", summary="An UUID value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     *
     * @param Owner $owner
     * @return \Illuminate\Http\Response
     */
    public function onlyInvestments(Owner $owner)
    {
      $investments = Investment::with('owner')->where('owner_id', $owner->id)->paginate(10);
      return response()->json($investments);
    }

    /**
     * @OA\Delete(
     *     tags={"Owners"},
     *     path="/api/owners/{id}",
     *     summary="Destroy an owner",
     *     @OA\Parameter(
     *         description="Owner uuid",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="uuid", value="98c660e0-90b4-4ddf-8543-2183380d1a10", summary="An UUID value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     *
     * @param Owner $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Owner $owner)
    {
        $owner->delete();
        return response()->json(['message'=> 'Owner and investments successfully removed!']);
    }

    /**
     * @OA\Post(
     *     tags={"Owners"},
     *     path="/api/owners",
     *     summary="Create an owner",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="email"
     *                 ),
     *                 example={"name": "Michael Desiato", "email": "michael@yourhonor.com"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK"
     *     )
     * )
     *
     * @param OwnerStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(OwnerStoreRequest $request)
    {
        try {
            $owner = Owner::create($request->all());
        } catch (\Exception $e) {
            return response()->json(['message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        return response()->json($owner, Response::HTTP_CREATED);
    }

}
