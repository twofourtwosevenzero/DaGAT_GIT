<?php

// app/Models/Signatory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Signatory extends Model
{
    use HasFactory;

    protected $fillable = [
        'QRC_ID',
        'Office_ID',
        'Status_ID',
        'Received_Date',
        'Signed_Date',
        'verification_count', // Add this line
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'Office_ID');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'Status_ID');
    }

    public function qrcode(): BelongsTo
    {
        return $this->belongsTo(QuickResponseCode::class, 'QRC_ID');
    }

    public function getProcessingTimeAttribute()
    {
        if ($this->Received_Date && $this->Signed_Date) {
            return Carbon::parse($this->Received_Date)->diffInMinutes(Carbon::parse($this->Signed_Date));
        }
        return null;
    }
}

