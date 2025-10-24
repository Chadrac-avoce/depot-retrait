<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    protected $fillable = ['user_id','type','montant','numero_mobile','destinataire_id','statut','reference'];
    public function user() { return $this->belongsTo(User::class); }
    public function destinataire() { return $this->belongsTo(User::class,'destinataire_id'); }
}
