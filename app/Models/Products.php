<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    public $table = "product";
    protected $primaryKey = "id";

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'fkProductID');
    }
    public function dtrans() {
        return $this->hasMany(Dtrans::class, 'fkProductID', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'fk_kategori'); // Make sure 'category_id' matches your foreign key in the product table
    }   
}
