<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\Menu;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    /**
     * Afficher le panier d'un utilisateur.
     */
    public function index($user_id)
    {
        $panier = Panier::where('user_id', $user_id)->first();

        if (!$panier) {
            return response()->json(['message' => 'Panier vide'], 200);
        }

        // Charger les menus liés via la table pivot
        $panier->load('menus');

        return response()->json($panier);
    }


    /**
     * Ajouter un menu au panier.
     */
    public function add(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'menu_id' => 'required|exists:menus,id',
        ]);

        // Récupérer le panier de l'utilisateur ou le créer
        $panier = Panier::firstOrCreate([
            'user_id' => $request->user_id,
        ]);

        // Attacher le menu dans la table pivot
        $panier->menus()->attach($request->menu_id);

        return response()->json([
            'message' => 'Menu ajouté au panier',
            'panier' => $panier->load('menus')
        ], 201);
    }


    /**
     * Retirer un menu du panier.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'menu_id' => 'required|exists:menus,id',
        ]);

        $panier = Panier::where('user_id', $request->user_id)->first();

        if (!$panier) {
            return response()->json(['message' => 'Panier introuvable'], 404);
        }

        $panier->menus()->detach($request->menu_id);

        return response()->json(['message' => 'Menu retiré du panier']);
    }


    /**
     * Vider le panier.
     */
    public function clear($user_id)
    {
        $panier = Panier::where('user_id', $user_id)->first();

        if (!$panier) {
            return response()->json(['message' => 'Panier déjà vide'], 200);
        }

        $panier->menus()->detach(); // supprime tout

        return response()->json(['message' => 'Panier vidé avec succès']);
    }
}
