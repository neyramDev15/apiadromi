<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Categorie;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //with permet de recuperer les categories de chaq menu
        $menus = Menu::with('categorie')->get();
        return response()->json([
            'success' => true,
            'data' => $menus
        ]);
    }

    /**
     * Store the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
        ]);

        
        $menus = Menu::create([
            'categorie_id' => $request->categorie_id,
            'nom' => $request->nom,
            'description' => $request->description,
            'image' => $request->image,
            'prix' => $request->prix,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Menu créé avec succès',
            'data' => $menus
        ], 201);
    }

   
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $menu = Menu::with('categorie')->find($id);
        if(!$menu){
            return response()->json([
                'success' => false,
                'message' => 'Menu introuvable'
            ], 404);
        }   
        return response()->json([
            'success' => true,
            'data' => $menu
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
          $menu = Menu::find($id);
        if(!$menu){
            return response()->json([
                'success' => false,
                'message' => 'Menu introuvable'
            ], 404);
        }
        //validation
        $request->validate([
    
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'prix' => 'required|numeric',
        ]);
        $menu->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Menu mis à jour avec succès',
            'data' => $menu
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $menu = Menu::find($id);
        if(!$menu){
            return response()->json([
                'success' => false,
                'message' => 'Menu introuvable'
            ], 404);
        }
        $menu->delete();
        return response()->json([
            'success' => true,
            'message' => 'Menu supprimé avec succès'
        ]);
    }
}
