<?php

namespace App\Http\Controllers\API;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Order;
use App\Orderdet;


class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        //if(\Gate::allows('isAdmin') || \Gate::allows('isUser')) {
        $this->authorize('isAdmin');
        return Order::with(['details','user'])->withCount('details')->orderBy('id', 'DESC')->paginate(300);
            //return User::paginate();
        //}
    }

    public function getUsers()
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
            'order_id' => 'required|numeric',
            'user_id'=> 'required|numeric',
            'saledt' => 'required|date',
            'salemethod' => 'required|string',
            'paymethod' => 'required|string',
            'shipmethod' => 'required|string',
            'tracking' => 'required|string',
            'status' => 'required|string',
            'shipcost' => 'required|numeric|between:0,99999.99',
            'prodcost' => 'required|numeric|between:0,999999.99',
            'totalcost' => 'required|numeric|between:0,999999.99'
        ]);

        return Order::create([
            'order_id' => $request['order_id'],
            'user_id' => $request['user_id'],
            'saledt' => $request['saledt'],
            'salemethod' => $request['salemethod'],
            'paymethod' => $request['paymethod'],
            'shipmethod' => $request['shipmethod'],
            'tracking' => $request['tracking'],
            'status' => $request['status'],
            'shipcost' => $request['shipcost'],
            'prodcost' => $request['prodcost'],
            'totalcost' => $request['totalcost'],
        ]);
    }

    public function addline(Request $request)
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
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $this->validate($request,[
            'order_id' => 'required|numeric',
            'user_id'=> 'required|numeric',
            'saledt' => 'required|date',
            'salemethod' => 'required|string',
            'paymethod' => 'required|string',
            'shipmethod' => 'required|string',
            'tracking' => 'required|string',
            'status' => 'required|string',
            'shipcost' => 'required|numeric|between:0,99999.99',
            'prodcost' => 'required|numeric|between:0,999999.99',
            'totalcost' => 'required|numeric|between:0,999999.99'
        ]);

        $order->update($request->all());
        return ['message' => 'Update user info' ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $order = Order::findOrFail($id);

        // delete the user
        $order->delete();

        return ['message' => 'Order Deleted'];
    }
    public function search(){
        if ($search = \Request::get('q')) {

            $order = Order::whereHas('user', function(Builder $query){
                $query->where('name','LIKE',"%$search%");
            })->with('details')->withCount('details')->paginate();

        }else{
            $order = Order::with(['details','user'])->withCount('details')->orderBy('id', 'DESC')->paginate();
        }
        return $order;
    }
}
