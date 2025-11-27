<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'status',
        'total',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function menus()
    {   
        return $this->belongsToMany(Menu::class, 'panier_menu')->withPivot('quantite')->withTimestamps();
    }
}
