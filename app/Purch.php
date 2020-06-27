<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Purch extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'id', 'user_id', 'purdt', 'currencytype', 'exchanger', 'taxcost', 'shipcost', 'quantity', 'prod_cost', 'total_mxn'
    ];

    public function details()
    {
        return $this->hasMany('App\Purchdet');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
