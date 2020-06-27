<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Order;
use App\Orderdet;

class OrderdetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $this->validate($request,[
            'order_id' => 'required|numeric',
            'order_line'=> 'required|numeric',
            'product_id' => 'required|string',
            'quantity' => 'required|numeric',
            'unitcost' => 'required',
        ]);

        return Orderdet::create([
            'order_id' => $request['order_id'],
            'order_line' => $request['order_line'],
            'product_id' => $request['product_id'],
            'quantity' => $request['quantity'],
            'unitcost' => $request['unitcost'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Orderdet  $orderdet
     * @return \Illuminate\Http\Response
     */
    public function show(Orderdet $orderdet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Orderdet  $orderdet
     * @return \Illuminate\Http\Response
     */
    public function edit(Orderdet $orderdet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Orderdet  $orderdet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $orderdet = Orderdet::findOrFail($id);

        $this->validate($request,[
            'order_line' => 'required|numeric',
            'product_id'=> 'required|string',
            'quantity' => 'required|numeric',
            'unitcost' => 'required|numeric|between:0,99999.99',
        ]);

        $orderdet->update($request->all());
        return ['message' => 'Update user info' ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Orderdet  $orderdet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $line = Orderdet::findOrFail($id);

        // delete the user
        $line->delete();

        return ['message' => 'Line Deleted'];
    }
}
