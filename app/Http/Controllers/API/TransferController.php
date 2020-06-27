<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Product;
use App\Purchdet;
use App\Orderdet;

class TransferController extends Controller{

    public function __construct()
    {
       $this->middleware('api');
    }

    public function index()
    {
        return User::get();
    }

    public function products()
    {
        return Product::get();
    }
}