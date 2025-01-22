<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dretur extends Model
{
    use HasFactory;

    protected $table = 'dretur';
    protected $primaryKey = 'DreturID';

    /**
     * Fillable fields for mass assignment.
     */
    protected $fillable = [
        'HreturID',
        'DtransID',
        'namaBarang',
        'Jumlah',
        'Satuan',
        'harga',
        'fotobarang',
        'harga',
        'Alasan',
    ];

    /**
     * Define a relationship with Hretur.
     */
    public function hretur()
    {
        return $this->belongsTo(Hretur::class, 'HreturID', 'HreturID');
    }

    /**
     * Define a relationship with the Dtrans model (sales detail).
     */
    public function salesDetail()
    {
        return $this->belongsTo(Dtrans::class, 'DtransID', 'id');
    }
}
