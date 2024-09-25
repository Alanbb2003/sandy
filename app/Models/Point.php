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
        'tanggalKaldaluarsaPoin',
        'saldoPoin'
    ];
}
