<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    public $table = "poin";

    protected $fillable = [
        'memberID',
        'htransID',
        'tanggalPemberianPoin',
        'jumlahPoin',
        'tipeTransaksi',
        'sumberPoin',
        'tanggalKadaluwarsaPoin',
        'saldoPoin'
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'memberID', 'memberID'); // Adjust the foreign key and local key as necessary
    }
}
