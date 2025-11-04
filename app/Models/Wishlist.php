<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    public $table = "wishlist";
    protected $primaryKey = "id";
    protected $fillable = ['fkUserID', 'fkProductID'];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'fkUserID');
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Products::class, 'fkProductID');
    }
}
