<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable

class Permission extends Model
{
    use HasApiTokens, Notifiable;

    
}
