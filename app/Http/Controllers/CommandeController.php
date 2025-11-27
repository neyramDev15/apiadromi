<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commandes;
use App\Models\Menu;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    return response()->json(Commandes::with(['menus','user','paiement'])->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $commandes = Commandes::create([
            'date_commande' => $request->date_commande,
            'statut' => $request->statut,
            'total' => $request->total,
            'user_id' => $request->user_id,
        ]);
        $commandes->menu()->attach($request->menu_ids);
        return response()->json([
            'success' => true,
            'data' => $commandes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $resquest->validate([
            'date_commande' => 'required|date',
            'statut' => 'required|string|max:255',
            'total' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'menu_ids' => 'required|array',
            'menu_ids.*' => 'exists:menus,id',
        ]);
        $total = 0;
        foreach ($request->menu_ids as $menu_id) {
            $menu = Menu::find($menu_id);
            $total += $menu->prix *item['quantite'];
    }
    $commandes = Commandes::create([
        'user_id' => $request->user_id,
        'date_commande'=>now(),
        'statut'=>'en attente',
        'total'=>$total,
    ]);
    foreach ($request->menus as $item) {
        $menu = Menu::find($item['menu_id']);
        $commandes->menus()->attach($item['id'],

        [
            'quantite' => $item['quantite'],
            'prix_unitaire' => $menu->prix
        
        ]
    );

    }
    return response()->json([
        'success' => true,
        'data' => $commandes
    ],201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $commande = Commandes::with(['menus','user','paiement'])->find($id);
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
        $coommande = Commandes::find($id);
        if (!$coommande) {
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
        $commande = Commandes::find($id);
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
