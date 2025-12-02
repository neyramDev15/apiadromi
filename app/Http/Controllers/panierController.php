<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Panier;
use App\Models\Menu;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    /**
     * Afficher tous les paniers.
     */
    public function index()
    {
        $paniers = Panier::with(['user', 'menus'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $paniers
        ]);
    }



    public function show($id)
    {
        $panier = Panier::with(['user', 'menus'])->find($id);
        if (!$panier) {
            return response()->json([
                'success' => false,
                'message' => 'Panier introuvable'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $panier
        ]);
    }

    /**
     * Récupérer le panier d'un utilisateur spécifique
     */
    public function getPanierByUser($user_id)
    {
        $panier = Panier::where('user_id', $user_id)->with(['user', 'menus'])->first();

        if (!$panier) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun panier trouvé pour cet utilisateur'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $panier
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Support pour un seul menu OU plusieurs menus
            if ($request->has('menu_id')) {
                // Format simple : un seul menu
                $request->validate([
                    'user_id' => 'required|exists:users,id',
                    'menu_id' => 'required|exists:menus,id',
                    'quantite' => 'required|integer|min:1'
                ]);
                $menus = [['menu_id' => $request->menu_id, 'quantite' => $request->quantite]];
            } else {
                // Format multiple : plusieurs menus
                $request->validate([
                    'user_id' => 'required|exists:users,id',
                    'menus' => 'required|array|min:1',
                    'menus.*.menu_id' => 'required|exists:menus,id',
                    'menus.*.quantite' => 'required|integer|min:1'
                ]);
                $menus = $request->menus;
            }

            // Récupérer ou créer le panier de l'utilisateur
            $panier = Panier::firstOrCreate(
                ['user_id' => $request->user_id],
                [
                    'statut' => 'en_cours',
                    'total' => 0
                ]
            );

            $ajouts = 0;
            $mises_a_jour = 0;

            // Traiter chaque menu
            foreach ($menus as $menuData) {
                $menu_id = $menuData['menu_id'];
                $quantite = $menuData['quantite'];

                // Vérifier si le menu existe déjà dans le panier
                $existant = $panier->menus()->where('menu_id', $menu_id)->first();

                if ($existant) {
                    // Augmenter la quantité
                    $panier->menus()->updateExistingPivot($menu_id, [
                        'quantite' => $existant->pivot->quantite + $quantite
                    ]);
                    $mises_a_jour++;
                } else {
                    // Ajouter un nouveau menu
                    $panier->menus()->attach($menu_id, [
                        'quantite' => $quantite
                    ]);
                    $ajouts++;
                }
            }

            // Message dynamique
            $message = '';
            if ($ajouts > 0) $message .= "$ajouts menu(s) ajouté(s)";
            if ($mises_a_jour > 0) {
                if ($message) $message .= ', ';
                $message .= "$mises_a_jour quantité(s) mise(s) à jour";
            }

            // Recharger les relations et recalculer le total
            $panier->load('menus');
            $this->updatePanierTotal($panier);

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $panier
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout au panier',
                'error' => $e->getMessage()
            ], 500);
        }
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

    /**
     * Mettre à jour un panier
     */
    public function update(Request $request, $id)
    {
        $panier = Panier::find($id);
        if (!$panier) {
            return response()->json(['message' => 'Panier introuvable'], 404);
        }

        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $panier->update($request->only(['user_id']));

        return response()->json([
            'success' => true,
            'message' => 'Panier mis à jour',
            'data' => $panier->load('menus')
        ]);
    }

    /**
     * Mettre à jour le total du panier
     */
    private function updatePanierTotal(Panier $panier)
    {
        $total = 0;
        foreach ($panier->menus as $menu) {
            $total += $menu->prix * $menu->pivot->quantite;
        }
        $panier->update(['total' => $total]);
    }

    /**
     * Supprimer un panier
     */
    public function destroy($id)
    {
        $panier = Panier::find($id);
        if (!$panier) {
            return response()->json(['message' => 'Panier introuvable'], 404);
        }

        // Supprimer les associations dans la table pivot
        $panier->menus()->detach();
        // Supprimer le panier
        $panier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Panier supprimé avec succès'
        ]);
    }
}
