<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = [
        'categorie_id',
        'nom',
        'description',
        'prix',
        'image',
    ];
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'Commande_menu')->withPivot('quantite')->withTimestamps();
    }
    public function paniers()
    {
        return $this->belongsToMany(Panier::class, 'panier_menu')->withPivot('quantite')->withTimestamps();
    } 

}
