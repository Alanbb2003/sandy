<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $table = "category";
    protected $primaryKey = "id";
    // protected $keyType = 'bigInt';

    public function products()
    {
        return $this->hasMany(Products::class, 'fk_kategori'); // Make sure 'category_id' matches your foreign key in the product table
    }
}
