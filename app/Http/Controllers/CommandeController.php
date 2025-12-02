<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Menu;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    return response()->json(Commande::with(['menus','user','paiement'])->get());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'menus' => 'required|array',
            'menus.*.menu_id' => 'required|exists:menus,id',
            'menus.*.quantite' => 'required|integer|min:1',
        ]);
        $total = 0;
        foreach ($request->menus as $item) {
            $menu = Menu::find($item['menu_id']);
            $total += $menu->prix * $item['quantite'];
        }
        
        $commande = Commande::create([
            'user_id' => $request->user_id,
            'date_commande' => now(),
            'statut' => 'en attente',
            'total' => $total,
        ]);
        
        foreach ($request->menus as $item) {
            $commande->menus()->attach($item['menu_id'], [
                'quantite' => $item['quantite']
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => $commande->load(['menus', 'user'])
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $commande = Commande::with(['menus','user','paiement'])->find($id);
        if (!$commande) {
            return response()->json([
                'success' => false,
                'message' => 'Commande introuvable'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $commande
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $commande = Commande::find($id);
        if (!$commande) {
            return response()->json([
                'success' => false,
                'message' => 'Commande introuvable'
            ], 404);
    }
    $request->validate([
        'statut' => 'nullable|string|max:255',
    ]);
        $commande->update([
            'statut' => $request->statut ?? $commande->statut,
        ]);
    return response()->json([
        'success' => true,
        'message' => 'Commande mise à jour avec succès',
        'data' => $commande
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $commande = Commande::find($id);
        if (!$commande) {
            return response()->json([
                'success' => false,
                'message' => 'Commande introuvable'
            ], 404);
        }
        //supprimer les associations dans la table pivot
        $commande->menus()->detach();
        //supprimer la commande
        $commande->delete();
        return response()->json([
            'success' => true,
            'message' => 'Commande supprimée avec succès'
        ]);
    }
}
