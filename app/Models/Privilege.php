<?php

// app/Models/Privilege.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    use HasFactory;

    protected $primaryKey = 'PRI_ID';

    protected $fillable = [
        'Privilege_Level',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'PRI_ID');
    }
}
