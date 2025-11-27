<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panier;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;


class Panier_menuController extends Controller
{
    /**
     * Afficher les menus dans un panier
     */
    public function index($panier_id)
    {
        $panier = Panier::with('menus')->find($panier_id);

        if (!$panier) {
            return response()->json(['message' => 'Panier introuvable'], 404);
        }

        return response()->json($panier);
    }

    /**
     * Ajouter un menu au panier
     */
    public function store(Request $request)
    {
        $request->validate([
            'panier_id' => 'required|exists:paniers,id',
            'menu_id' => 'required|exists:menus,id',
            'quantite' => 'nullable|integer|min:1'
        ]);

        $panier = Panier::find($request->panier_id);

        // Vérifier si le menu existe déjà dans le pivot
        $existant = DB::table('panier_menu')
            ->where('panier_id', $request->panier_id)
            ->where('menu_id', $request->menu_id)
            ->first();

        if ($existant) {
            // On augmente la quantité
            DB::table('panier_menu')
                ->where('id', $existant->id)
                ->update([
                    'quantite' => $existant->quantite + ($request->quantite ?? 1)
                ]);

            return response()->json(['message' => 'Quantité mise à jour'], 200);
        }

        // Ajouter dans le pivot
        $panier->menus()->attach($request->menu_id, [
            'quantite' => $request->quantite ?? 1,
        ]);

        return response()->json(['message' => 'Menu ajouté au panier'], 201);
    }

    /**
     * Modifier la quantité d’un menu dans le panier
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1'
        ]);

        $ligne = DB::table('panier_menu')->where('id', $id)->first();

        if (!$ligne) {
            return response()->json(['message' => 'Élément non trouvé'], 404);
        }

        DB::table('panier_menu')->where('id', $id)->update([
            'quantite' => $request->quantite
        ]);

        return response()->json(['message' => 'Quantité mise à jour']);
    }

    /**
     * Supprimer un menu du panier
     */
    public function destroy($id)
    {
        $ligne = DB::table('panier_menu')->where('id', $id)->first();

        if (!$ligne) {
            return response()->json(['message' => 'Élément non trouvé'], 404);
        }

        DB::table('panier_menu')->where('id', $id)->delete();

        return response()->json(['message' => 'Menu retiré du panier']);
    }
}