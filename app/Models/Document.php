<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 
        'DT_ID', 
        'Status_ID', 
        'Description', 
        'Date_Created', 
        'Date_Approved', 
        'Document_File'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'DT_ID');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'Status_ID');
    }

    public function qrcode()
    {
        return $this->hasOne(QuickResponseCode::class, 'Docu_ID');
    }

    public function signatories()
    {
        return $this->hasManyThrough(
            Signatory::class,
            QuickResponseCode::class,
            'Docu_ID', // Foreign key on QuickResponseCode table
            'QRC_ID',  // Foreign key on Signatory table
            'id',      // Local key on Document table
            'id'       // Local key on QuickResponseCode table
        );
    }
}
