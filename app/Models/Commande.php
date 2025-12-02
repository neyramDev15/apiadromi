<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commande extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date_commande',
        'statut',
        'total',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'commande_menu')->withPivot('quantite')->withTimestamps();
    }
    
    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }
}
