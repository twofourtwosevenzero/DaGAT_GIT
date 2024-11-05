<?php

// app/Models/ActivityLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'Docu_ID',
        'Sign_ID',
        'action',
        'Timestamp',
        'reason',
        'requested_by' // Add this to allow mass assignment
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'Docu_ID');
    }

    public function signatory(): BelongsTo
    {
        return $this->belongsTo(Signatory::class, 'Sign_ID');
    }
    // New relationship to fetch the office that requested the revision
    public function requestedOffice(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'requested_by');
    }
    
    
}
