<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Htrans extends Model
{
    use HasFactory;
    public $table = "htrans";

    public function items()
    {
        return $this->hasMany(Dtrans::class, 'fkHtransID', 'id');
    }
    public function dtrans()
    {
        return $this->hasMany(Dtrans::class, 'fkHtransID', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'fkUserID');
    }
}
