<?php

namespace App\Http\Controllers\API;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\Purch;
use App\Product;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        //if(\Gate::allows('isAdmin') || \Gate::allows('isUser')) {
        $this->authorize('isAdmin');
        $orders = Order::select('status', DB::raw('SUM(prodcost) as total'))->groupBy('status')->get();
        $sales = Purch::select('status', DB::raw('SUM(total_mxn) as total'))->groupBy('status')->get();
        $product = Product::select(DB::raw('inventory * price as total'))->get();
        $totals = $product->sum('total');
        $balance =  round($orders->sum('total')- $sales->sum('total'), 2) ;
        $mainbalance = round($totals + $balance,2) ;



        return array($orders, $sales, $totals, $balance, $mainbalance) ;

    }


}
