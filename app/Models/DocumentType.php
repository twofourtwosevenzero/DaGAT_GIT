<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; 
    protected $fillable = ['DT_Type'];

    public function signatories()
    {
        return $this->belongsToMany(Office::class, 'document_type_signatories', 'document_type_id', 'office_id');
    }

}
