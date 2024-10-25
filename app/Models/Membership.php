<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;
    public $table = "membership";
    protected $primaryKey = "memberID";

    protected $fillable = [
        'fkUserID',
        'tanggalDaftar',
        'tanggalAkhir',
        'statusMembership',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'fkUserID', 'id');
    }
    public function points()
    {
        return $this->hasMany(Point::class, 'memberID', 'memberID')->orderBy('tanggalPemberianPoin', 'desc'); // Adjust the foreign key and local key as necessary
    }
    public function getTotalPointsAttribute()
    {
        return $this->points()->sum('jumlahPoin');
    }
}
