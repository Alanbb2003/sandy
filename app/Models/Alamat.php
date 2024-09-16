<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;
    public $table = "address_book";
    protected $primaryKey = "id";
    protected $fillable = [
       'fkUserID', 'namaDepan', 'namaBelakang', 'noHP', 'provinsi', 
        'kota', 'kecamatan', 'kelurahan', 'kodePos', 'detailAlamat'
    ];
}
