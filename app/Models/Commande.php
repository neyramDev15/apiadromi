<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date_commande',
        'status',
        'total',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'Commande_menu')->withPivot('quantite')->withTimestamps();
    }                               
}
