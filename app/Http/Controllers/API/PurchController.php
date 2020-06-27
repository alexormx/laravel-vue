<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Purch;
use App\Purchdet;

class PurchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        //if(\Gate::allows('isAdmin') || \Gate::allows('isUser')) {
        $this->authorize('isAdmin');
        return Purch::with(['details','user'])->withCount('details')->orderBy('id', 'DESC')->paginate(6);
        //return User::paginate();
        //}
    }

    public function getUsers2()
    {
        //if(\Gate::allows('isAdmin') || \Gate::allows('isUser')) {

        $this->authorize('isAdmin');
        return User::orderBy('name', 'ASC')->get();
        //return User::paginate();
        //}
    }
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
    public function store(Request $request)
    {
        $this->validate($request,[
            'user_id'=> 'required|numeric',
            'purdt' => 'required|date',
            'currencytype' => 'required|string',
            'exchanger' => 'required|numeric|between:0,99999.99',
            'taxcost' => 'required|numeric|between:0,99999.99',
            'shipcost' => 'required|numeric|between:0,99999.99',
            'prod_cost' => 'required|numeric|between:0,999999.99',
            'total_mxn' => 'required|numeric|between:0,999999.99'
        ]);

        return Purch::create([
            'user_id' => $request['user_id'],
            'purdt' => $request['purdt'],
            'currencytype' => $request['currencytype'],
            'exchanger' => $request['exchanger'],
            'taxcost' => $request['taxcost'],
            'shipcost' => $request['shipcost'],
            'prod_cost' => $request['prod_cost'],
            'total_mxn' => $request['total_mxn'],
        ]);
    }

    public function addline(Request $request)
    {
        $this->validate($request,[
            'purch_id' => 'required|numeric',
            'poline'=> 'required|numeric',
            'product_id' => 'required|string',
            'quantity' => 'required|numeric',
            'unitcost' => 'required',
        ]);

        return Orderdet::create([
            'purch_id' => $request['purch_id'],
            'poline' => $request['poline'],
            'product_id' => $request['product_id'],
            'quantity' => $request['quantity'],
            'unitcost' => $request['unitcost'],
        ]);
    }
    public function show(Purch $purch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purch  $purch
     * @return \Illuminate\Http\Response
     */
    public function edit(Purch $purch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purch  $purch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Purch::findOrFail($id);

        $this->validate($request,[
            'user_id'=> 'required|numeric',
            'purdt' => 'required|date',
            'currencytype' => 'required|string',
            'exchanger' => 'required|numeric|between:0,99999.99',
            'taxcost' => 'required|numeric|between:0,99999.99',
            'shipcost' => 'required|numeric|between:0,99999.99',
            'prod_cost' => 'required|numeric|between:0,999999.99',
            'total_mxn' => 'required|numeric|between:0,999999.99'
        ]);

        $order->update($request->all());
        return ['message' => 'Update user info' ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purch  $purch
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $order = Purch::findOrFail($id);

        // delete the user
        $order->delete();

        return ['message' => 'Order Deleted'];
    }
}
