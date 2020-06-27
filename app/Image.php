<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Image extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'product_id', 'image',
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

}
