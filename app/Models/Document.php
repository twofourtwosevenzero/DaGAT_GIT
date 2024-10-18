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

    // Define the relationship with Signatory
    public function signatories()
    {
    return $this->hasMany(Signatory::class, 'QRC_ID', 'id');
    }

    public function qrcodes()
    {
        return $this->hasMany(QuickResponseCode::class, 'Docu_ID');
    }

}
