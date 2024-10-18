<?php

// app/Models/Office.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = ['Office_Name', 'Office_Pin'];

    public function signatories()
    {
        return $this->hasMany(Signatory::class, 'Office_ID');
    }

    public function documentTypes()
    {
        return $this->belongsToMany(DocumentType::class, 'document_type_signatories', 'office_id', 'document_type_id');
    }

}
