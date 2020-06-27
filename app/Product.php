<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasApiTokens, Notifiable;
    
    protected $fillable = [
        'product_id', 'sku', 'name', 'brand', 'category_id', 'price',
    ];

    public function purchases()
    {
        return $this->hasMany('App\Purchdet');
    }

    public function orders()
    {
        return $this->hasMany('App\Orderdet', 'product_id', 'product_id');
    }

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function sales()
    { 
        return $this->hasOne(Orderdet::class, 'product_id', 'product_id')
        ->selectRaw('product_id, sum(quantity) as totals')
        ->groupBy('product_id')->withDefault([
            'totals' => '0',
        ]);
    }

    public function purches()
    {
        return $this->hasOne(Purchdet::class, 'product_id', 'product_id')
            ->selectRaw('product_id, sum(quantity) as totals')
            ->groupBy('product_id')->withDefault([
                'totals' => '0',
            ]);
    }

}
