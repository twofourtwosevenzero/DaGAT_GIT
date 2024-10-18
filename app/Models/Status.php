<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['Status_Name'];

    public function documents()
    {
        return $this->hasMany(Document::class, 'Status_ID');
    }

    public function signatories()
    {
        return $this->hasMany(Signatory::class, 'Status_ID');
    }
}
