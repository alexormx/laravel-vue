<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Purch;
use App\Purchdet;

class PurchdetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            'purch_id' => 'required|numeric',
            'poline'=> 'required|numeric',
            'product_id' => 'required|string',
            'quantity' => 'required|numeric',
            'unitcost' => 'required',
        ]);

        return Purchdet::create([
            'purch_id' => $request['purch_id'],
            'poline' => $request['poline'],
            'product_id' => $request['product_id'],
            'quantity' => $request['quantity'],
            'unitcost' => $request['unitcost'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchdet  $purchdet
     * @return \Illuminate\Http\Response
     */
    public function show(Purchdet $purchdet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purchdet  $purchdet
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchdet $purchdet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchdet  $purchdet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $orderdet = Purchdet::findOrFail($id);

        $this->validate($request,[
            'poline'=> 'required|numeric',
            'product_id' => 'required|string',
            'quantity' => 'required|numeric',
            'unitcost' => 'required',
        ]);

        $orderdet->update($request->all());
        return ['message' => 'Update user info' ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchdet  $purchdet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchdet $purchdet)
    {
        $this->authorize('isAdmin');
        $line = Purchdet::findOrFail($id);

        // delete the user
        $line->delete();

        return ['message' => 'Line Deleted'];
    }
}
