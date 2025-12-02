<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panier;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;


class PanierMenuController extends Controller
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
        try {
            // D'abord essayer par l'ID de la table pivot
            $ligne = DB::table('panier_menu')->where('id', $id)->first();

            if (!$ligne) {
                return response()->json([
                    'success' => false,
                    'message' => 'Élément non trouvé dans le panier',
                    'debug' => "Aucun enregistrement trouvé avec l'ID $id dans panier_menu"
                ], 404);
            }

            // Récupérer les infos avant suppression pour le message
            $panier = Panier::find($ligne->panier_id);
            $menu = Menu::find($ligne->menu_id);

            // Supprimer l'enregistrement
            DB::table('panier_menu')->where('id', $id)->delete();

            // Recalculer le total du panier
            if ($panier) {
                $panier->load('menus');
                $total = 0;
                foreach ($panier->menus as $menu_item) {
                    $total += $menu_item->prix * $menu_item->pivot->quantite;
                }
                $panier->update(['total' => $total]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Menu retiré du panier avec succès',
                'data' => [
                    'panier_id' => $ligne->panier_id,
                    'menu_supprime' => $menu ? $menu->nom : 'Menu ID ' . $ligne->menu_id,
                    'quantite_supprimee' => $ligne->quantite
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un menu spécifique d'un panier spécifique
     */
    public function removeMenuFromPanier(Request $request)
    {
        $request->validate([
            'panier_id' => 'required|exists:paniers,id',
            'menu_id' => 'required|exists:menus,id'
        ]);

        $ligne = DB::table('panier_menu')
            ->where('panier_id', $request->panier_id)
            ->where('menu_id', $request->menu_id)
            ->first();

        if (!$ligne) {
            return response()->json([
                'success' => false,
                'message' => 'Ce menu n\'est pas dans ce panier'
            ], 404);
        }

        DB::table('panier_menu')
            ->where('panier_id', $request->panier_id)
            ->where('menu_id', $request->menu_id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu retiré du panier'
        ]);
    }
}