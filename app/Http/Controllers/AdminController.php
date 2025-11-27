<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Afficher tous les admins
     */
    public function index()
    {
        $admins = Admin::all();
        return response()->json($admins);
    }

    /**
     * Créer un nouvel admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'mot_de_passe' => 'required|string|min:6',
            'role' => 'required|string',
            'telephone' => 'nullable|string|max:20'
        ]);

        $admin = Admin::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'role' => $request->role,
            'telephone' => $request->telephone,
        ]);

        return response()->json(['message' => 'Admin créé', 'admin' => $admin], 201);
    }

    /**
     * Afficher un admin spécifique
     */
    public function show($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin non trouvé'], 404);
        }
        return response()->json($admin);
    }

    /**
     * Mettre à jour un admin
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin non trouvé'], 404);
        }

        $request->validate([
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:admins,email,' . $id,
            'mot_de_passe' => 'nullable|string|min:6',
            'role' => 'nullable|string',
            'telephone' => 'nullable|string|max:20'
        ]);

        if ($request->nom) $admin->nom = $request->nom;
        if ($request->prenom) $admin->prenom = $request->prenom;
        if ($request->email) $admin->email = $request->email;
        if ($request->mot_de_passe) $admin->mot_de_passe = Hash::make($request->mot_de_passe);
        if ($request->role) $admin->role = $request->role;
        if ($request->telephone) $admin->telephone = $request->telephone;

        $admin->save();

        return response()->json(['message' => 'Admin mis à jour', 'admin' => $admin]);
    }

    /**
     * Supprimer un admin
     */
    public function destroy($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin non trouvé'], 404);
        }

        $admin->delete();
        return response()->json(['message' => 'Admin supprimé']);
    }
}
