<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class paiement extends Model
{
   use HasFactory;
   protected $fillable = [
       'commande_id',
       'montant',
       'methode_paiement',
       'status',
   ];
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
