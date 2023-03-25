<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Investment;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $owners = Owner::with('investments')
        ->where('id',$search)
        ->orWhere('email', 'like', '%'.$search.'%')
        ->paginate(10);

        return response()->json($owners);
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Owner $owner)
    {
      $owner->investments;
      return response()->json($owner);
    }

    /**
     * Returns only owner investments
     * @param Owner $owner
     * @return \Illuminate\Http\Response
     */
    public function onlyInvestments(Owner $owner)
    {
      $investments = Investment::where('owner_id', $owner->id)->paginate(10);
      return response()->json($investments);
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Owner $owner)
    {
        $owner->delete();
        return response()->json(['message'=> 'Owner and investments successfully removed!']);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


}
