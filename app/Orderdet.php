<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Orderdet extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'id', 'order_line', 'order_id', 'product_id', 'quantity', 'unitcost',
    ];

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
