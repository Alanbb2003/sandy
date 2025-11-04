<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class retur extends Model
{
    use HasFactory;
    public $table = "retur";

    protected $fillable = [
        'fkHeaderID',
        'fkUserID',
        'fkDtransID',
        'fotoBarang',
        'alasanRetur',
        'tanggalRetur',
        'jumlahBarangRetur',
        'satuanBarangRetur',
        'hargaPerBarang',
        'subtotal',
        'TipePengembalian',
        'bankName',
        'accountNumber',
        'status'
    ];
    public function htrans()
    {
        return $this->belongsTo(Htrans::class, 'fkHeaderID', 'id');
    }
    public function dtrans()
    {
        return $this->belongsTo(Dtrans::class, 'fkDtransID', 'id'); // dtransID is the foreign key in retur
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'fkUserID', 'id');
    }
}
