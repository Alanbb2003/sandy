<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dtrans extends Model
{
    use HasFactory;
    public $table = "dtrans";

    public function htrans()
    {
        return $this->belongsTo(Htrans::class, 'fkHtransID', 'id');
    }
    public function product() {
        return $this->belongsTo(Products::class, 'fkProductID', 'id');
    }

}
