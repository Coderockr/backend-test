<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investment as Investment;
use App\Http\Resources\Investment as InvestmentResource;
use DateTime;
use Illuminate\Support\Facades\Validator;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $investments = Investment::paginate(15);
        return InvestmentResource::collection($investments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $investments = new Investment;
        $investments->owner = $request->input('owner');
        $investments->amount = $request->input('amount');
        $now = new DateTime("now");
        $investments->create_date = $request->input('create_date');
        
        $validate = Validator::make($request->all(), [
            'owner'         => 'required',
            'amount'        => 'required|numeric|gte:0',
            'create_date'   => 'required|date|before_or_equal:' . $now->format("Y-m-d")
        ]);

        if ($validate->fails()) {
            return response(json_encode($validate->errors()), 400)
                            ->header('Content-Type', 'application/json');
        }

        if( $investments->save() ){
          return new InvestmentResource($investments);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $investments = Investment::findOrFail( $id );
        return new InvestmentResource( $investments );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
