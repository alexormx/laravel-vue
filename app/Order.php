<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'id', 'order_id', 'user_id', 'saledt', 'salemethod', 'paymethod', 'shipmethod', 'tracking', 'shipcost', 'prodcost', 'totalcost', 'status'
    ];

    public function details()
    {
        return $this->hasMany('App\Orderdet');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
