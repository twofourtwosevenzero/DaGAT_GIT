<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedFile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'path', 'document_type_id', 'approved_date',];
    protected $casts = ['approved_date' => 'date',];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
