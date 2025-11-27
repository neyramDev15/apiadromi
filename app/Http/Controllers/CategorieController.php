<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    /** 
     * Display all categories 
     */
    public function index()
    {
        return response()->json(Categorie::all());
    }

    /**
     * Store a new category
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create
        $categorie = Categorie::create([
            'nom' => $request->nom,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'data' => $categorie
        ], 201);
    }

    /**
     * Display a specific category
     */
    public function show($id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie introuvable'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $categorie
        ]);
    }

    /**
     * Update a category
     */
    public function update(Request $request, $id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée'
            ], 404);
        }

        $request->validate([
            'nom' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $categorie->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $categorie
        ]);
    }

    /**
     * Delete a category
     */
    public function destroy($id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée'
            ], 404);
        }

        $categorie->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catégorie supprimée avec succès'
        ]);
    }
}
