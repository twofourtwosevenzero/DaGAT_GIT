<?php

// app/Models/ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'Docu_ID',
        'Sign_ID',
        'action',
        'Timestamp',
        'reason',
        'user_id',
        'requested_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'Docu_ID');
    }

    public function signatory()
    {
        return $this->belongsTo(Signatory::class, 'Sign_ID');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'requested_by');
    }
}
