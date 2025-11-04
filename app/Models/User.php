<?php

namespace App\Models;

use Carbon\Carbon;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'firstName',
        'lastName',
        'role',
        'tanggalLahir',
        'noHp',  
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function role(): Attribute{
        return new Attribute(
            get: fn($value) => ["user","admin"][$value],
        );
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'fkUserID');
    }
    
    public function htrans()
    {
        return $this->hasMany(Htrans::class, 'fkUserID', 'id');
    }
    public function membership()
    {
        return $this->hasOne(Membership::class, 'fkUserID', 'id');
    }
    public function scopeHasBirthdayToday($query)
    {
        $today = Carbon::today()->format('m-d');
        return $query->whereNotNull('tanggalLahir')
                    ->whereRaw('DATE_FORMAT(tanggalLahir, "%m-%d") = ?', [$today]);
    }
}
