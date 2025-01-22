<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hretur extends Model
{
    use HasFactory;
    protected $table = 'hretur';
    protected $primaryKey = 'HReturID';

    /**
     * Fillable fields for mass assignment.
     */
    protected $fillable = [
        'HtransID',
        'userID',
        'TanggalRetur',
        'TotalHargaRetur',
        'discount',
        'Status',
        'FKPenerima',
        'jumlahBarangRetur',
        'TipePengembalian',
        'statusPerubahan',
        'AlasanPerubahan'
    ];

    /**
     * Define a one-to-many relationship with Dretur.
     */
    public function Dretur()
    {
        return $this->hasMany(Dretur::class, 'HreturID', 'HReturID');
    }

    /**
     * Define a relationship with the Htrans model (sales header).
     */
    public function htrans()
    {
        return $this->belongsTo(Htrans::class, 'HtransID'); // Adjust 'htrans_id' to your actual foreign key
    }
    /**
     * Define a relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }
}
