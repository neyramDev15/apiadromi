<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;

class PaiementController extends Controller
{
    // Récupérer tous les paiements
    public function index()
    {
        $paiements = Paiement::all();
        return response()->json($paiements);
    }

    // Créer un nouveau paiement
    public function store(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'commande_id' => 'required|exists:commandes,id',
                'montant' => 'required|numeric|min:0',
                'methode_paiement' => 'required|string|max:255',
                'status' => 'required|string|max:255'
            ]);

            $paiement = Paiement::create([
                'commande_id' => $request->commande_id,
                'montant' => $request->montant,
                'methode_paiement' => $request->methode_paiement,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paiement créé avec succès',
                'data' => $paiement->load('commande')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du paiement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Afficher un paiement spécifique
    public function show($id)
    {
        $paiement = Paiement::find($id);
        if (!$paiement) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }
        return response()->json($paiement);
    }

    // Mettre à jour un paiement
    public function update(Request $request, $id)
    {
        $paiement = Paiement::find($id);
        if (!$paiement) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }

        $request->validate([
            'montant' => 'sometimes|numeric',
            'methode_paiement' => 'sometimes|string|max:255',
            'status' => 'sometimes|string|max:255'
        ]);

        $paiement->update($request->all());
        return response()->json($paiement);
    }

    // Supprimer un paiement
    public function destroy($id)
    {
        $paiement = Paiement::find($id);
        if (!$paiement) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }

        $paiement->delete();
        return response()->json(['message' => 'Paiement supprimé']);
    }
}
