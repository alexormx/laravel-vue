<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Purchdet extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'id', 'purch_id', 'poline', 'product_id', 'quantity', 'unitcost',
    ];

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function purchase()
    {
        return $this->belongsTo('App\Purch');
    }
}
