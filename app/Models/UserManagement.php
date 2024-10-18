<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'position_id', 'password', 'PRI_ID'
    ];
    
    // Define any relationships if needed
    // For example, if Position is another model:

}
