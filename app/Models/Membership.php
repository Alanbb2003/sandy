<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;
    public $table = "membership";
    protected $primaryKey = "memberID";

    public function user()
    {
        return $this->belongsTo(User::class, 'fkUserID', 'id');
    }
}
