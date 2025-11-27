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
         $menus = Menu::with('categories')->get();
       return reponse()->json([
        'success' => true,
        'data' => $menus
       ]) ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        $menus = Menu::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'iamge' => $request->image,
            'prix' => $request->prix,
        ]);
        return reponse()->json([
            'success' => true,
            'data' => $menus
           ]) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $menu =Menu::with('categories')->find($id);
        if(!$menu){
            return reponse()->json([
                'success' => false,
                'message' => 'Menu introuvable'
               ],404) ;
        }   
        return reponse()->json([
            'success' => true,
            'data' => $menu
           ]) ; 
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
            return reponse()->json([
                'success' => false,
                'message' => 'Menu introuvable'
               ],404) ;
        }
        //validation
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'prix' => 'required|numeric',
        ]);
        $menu->update($request->all());
        return reponse()->json([
            'success' => true,
            'data' => $menu
           ]) ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $menu = Menu::find($id);
        if(!$menu){
            return reponse()->json([
                'success' => false,
                'message' => 'Menu introuvable'
               ],404) ;
        }
        $menu->delete();
        return reponse()->json([
            'success' => true,
            'message' => 'Menu supprimé avec succès'
           ]) ;
    }
}
