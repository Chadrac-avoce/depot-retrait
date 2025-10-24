<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom','prenom','telephone','email','password','role'
    ];

    protected $hidden = [
        'password','remember_token',
    ];

    // relations
    public function wallet() {
        return $this->hasOne(Wallet::class);
    }
    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
