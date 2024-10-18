<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'signatory_id',
        'revision_type',
        'revision_reason',
        'requested_by',
    ];
}
