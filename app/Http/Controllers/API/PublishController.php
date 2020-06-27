<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Purchdet;
use App\Orderdet;

class PublishController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
       $this->middleware('auth:api');
    }

    public function index()
    {
       //if(\Gate::allows('isAdmin') || \Gate::allows('isUser')) {
            $this->authorize('isAdmin');
            return Product::where('inventory', '!=' , 0)
                 //->selectRaw("sales.totals as total_address")
                ->orderBy('brand', 'DESC')
                ->orderBy('name', 'ASC')
                ->get();
            //return User::paginate();
        //}
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
            'product_id' => 'required|string|max:191',
            'sku'=> 'required|numeric',
            'name' => 'required|string',
            'brand' => 'required|string',
            //'category_id' => 'required|integer'
            'price' => 'required|numeric|between:0,9999.99'
        ]);

        return Product::create([
            'product_id' => $request['product_id'],
            'sku' => $request['sku'],
            'name' => $request['name'],
            'brand' => $request['brand'],
            //'category_id' => $request['category_id'],
            'price' => $request['price'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $this->validate($request,[
            'product_id' => 'required|string|max:191',
            'sku'=> 'required|numeric',
            'name' => 'required|string',
            'brand' => 'required|string',
            'price' => 'required|numeric|between:0,9999.99'
        ]);

        $product->update($request->all());
        return ['message' => 'Update user info' ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $product = Product::findOrFail($id);

        // delete the user
        $product->delete();

        return ['message' => 'User Deleted'];
    }
    public function search(){
        if ($search = \Request::get('q')) {

            $product =Product::with(['sales', 'purches'])->where(function($query) use ($search){
                    $query->where('product_id','LIKE',"%$search%")
                        ->orWhere('sku','LIKE',"%$search%")
                        ->orWhere('name','LIKE',"%$search%")
                         ->orWhere('brand','LIKE',"%$search%");
                })->paginate();
        }else{
            $product = Product::with(['sales', 'purches'])
                ->orderBy('id', 'DESC')->get();
        }
        return $product;
    }

    public function actualizar(){
        $compras = DB::table('purchdets')
                    ->select('product_id', DB::raw('SUM(quantity) as total'))
                    ->groupBy('product_id')->get();

        foreach ($compras as $item){
            $actualc = Product::where('product_id', $item->product_id)->first();
            $actualc->purchase = $item->total;
            $actualc->save();
        }
        $ventas = DB::table('orderdets')
            ->select('product_id', DB::raw('SUM(quantity) as total'))
            ->groupBy('product_id')->get();

        foreach ($ventas as $ite) {
            $actualz = Product::where('product_id', $ite->product_id)->first();
            if ($actualz->saled === 0) {
                $actualz->saled = $ite->total;
                $actualz->inventory = $actualz->purchase - $actualz->saled;
            }
            $actualz->save();
        }

        $productos = DB::table('products')
            ->orderBy('id', 'ASC')->get();

        foreach ($productos as $unique){
            $dummys = Product::where('product_id', $unique->product_id)->first();
            $dummys->inventory = $unique->purchase - $unique->saled;
            $dummys->save();
        }
    }
}
