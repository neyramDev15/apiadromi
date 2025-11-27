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
        // Validation des données
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'montant' => 'required|numeric',
            'methode' => 'required|string|max:255',
            'statut' => 'required|string|max:255'
        ]);

        $paiement = Paiement::create([
            'commande_id' => $request->commande_id,
            'montant' => $request->montant,
            'methode' => $request->mode_paiement,
            'statut' => $request->statut
        ]);

        return response()->json($paiement, 201);
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
            'methode' => 'sometimes|string|max:255',
            'statut' => 'sometimes|string|max:255'
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
