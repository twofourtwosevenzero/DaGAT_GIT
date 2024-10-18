<?php
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    
    class QuickResponseCode extends Model
    {
        use HasFactory;
        protected $table = 'qrcodes'; // Make sure this matches your table name
        protected $primaryKey = 'id'; // Assuming `id` is the primary key
        protected $fillable = [
            'Docu_ID',
            'QR_Image',
            'Date_Created',
            'Usage_Count'
        ];
    
        public function document()
        {
            return $this->belongsTo(Document::class, 'id');
        }
    
        public function signatories()
        {
            return $this->hasMany(Signatory::class, 'QRC_ID');
        }
    }
    