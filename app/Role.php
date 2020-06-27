<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'display_name',
    ];

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function permisions()
    {
        return $this->hasMany('App\Permission');
    }

}
