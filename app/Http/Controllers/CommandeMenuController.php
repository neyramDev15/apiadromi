<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commandes;
use App\Models\Menu;
use illuminate\Support\Facades\DB;

class CommandeMenuController extends Controller
{
    /**
     * Afficher tous les menus d’une commande
     */
    public function index($commande_id)
    {
        $commande = Commande::with('menus')->find($commande_id);

        if (!$commande) {
            return response()->json(['message' => 'Commande introuvable'], 404);
        }

        return response()->json($commande);
    }

    /**
     * Ajouter un menu dans la commande
     */
    public function store(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'menu_id'     => 'required|exists:menus,id',
            'quantite'    => 'required|integer|min:1',
        ]);

        $commande = Commande::find($request->commande_id);
        $menu = Menu::find($request->menu_id);

        // Calcule le prix total de cet article
        $prix_total = $menu->prix * $request->quantite;

        // Vérifie si le menu est déjà inclus dans la commande
        $exist = DB::table('commande_menu')
            ->where('commande_id', $request->commande_id)
            ->where('menu_id', $request->menu_id)
            ->first();

        if ($exist) {
            // Mise à jour de la quantité et du prix total
            DB::table('commande_menu')->where('id', $exist->id)->update([
                'quantite' => $exist->quantite + $request->quantite,
                'prix_total' => $menu->prix * ($exist->quantite + $request->quantite),
            ]);

            return response()->json(['message' => 'Quantité mise à jour'], 200);
        }

        // Sinon, on ajoute dans le pivot
        $commande->menus()->attach($request->menu_id, [
            'quantite' => $request->quantite,
            'prix_total' => $prix_total
        ]);

        return response()->json(['message' => 'Menu ajouté à la commande'], 201);
    }

    /**
     * Modifier la quantité d’un plat dans la commande
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1'
        ]);

        $ligne = DB::table('commande_menu')->where('id', $id)->first();

        if (!$ligne) {
            return response()->json(['message' => 'Élément non trouvé'], 404);
        }

        $menu = Menu::find($ligne->menu_id);

        DB::table('commande_menu')->where('id', $id)->update([
            'quantite' => $request->quantite,
            'prix_total' => $menu->prix * $request->quantite,
        ]);

        return response()->json(['message' => 'Quantité mise à jour']);
    }

    /**
     * Supprimer un menu de la commande
     */
    public function destroy($id)
    {
        $ligne = DB::table('commande_menu')->where('id', $id)->first();

        if (!$ligne) {
            return response()->json(['message' => 'Élément non trouvé'], 404);
        }

        DB::table('commande_menu')->where('id', $id)->delete();

        return response()->json(['message' => 'Menu retiré de la commande']);
    }
}