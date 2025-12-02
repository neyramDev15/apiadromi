<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PanierMenuController;
use App\Http\Controllers\CommandeMenuController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PaiementController;

Route::get('/test', function () {
    return ['message' => 'API OK'];
});

Route::get('get_all_users', [UserController::class, 'index']);
Route::post('add_user', [UserController::class, 'store']);
Route::get('get_user/{id}', [UserController::class, 'show']);
Route::put('edit_user/{id}', [UserController::class, 'update']);
Route::delete('delete_user/{id}', [UserController::class, 'destroy']);

Route::get('get_all_admins', [AdminController::class, 'index']);
Route::post('add_admin', [AdminController::class, 'store']);
Route::get('get_admin/{id}', [AdminController::class, 'show']);
Route::put('edit_admin/{id}', [AdminController::class, 'update']);
Route::delete('delete_admin/{id}', [AdminController::class, 'destroy']);

Route::get('get_panier_menus/{id}', [PanierMenuController::class, 'index']);
Route::post('add_menu_to_panier', [PanierMenuController::class, 'store']);
Route::put('edit_panier_menu/{id}', [PanierMenuController::class, 'update']);
Route::delete('remove_menu_from_panier/{id}', [PanierMenuController::class, 'destroy']);
Route::delete('remove_menu_from_panier_by_ids', [PanierMenuController::class, 'removeMenuFromPanier']);


Route::get('get_commande_menus/{id}', [CommandeMenuController::class, 'index']);
Route::post('add_menu_to_commande', [CommandeMenuController::class, 'store']);
Route::put('edit_commande_menu/{id}', [CommandeMenuController::class, 'update']);
Route::delete('remove_menu_from_commande/{id}', [CommandeMenuController::class, 'destroy']);

Route::get('get_all_commandes', [CommandeController::class, 'index']);
Route::post('add_commande', [CommandeController::class, 'store']);
Route::get('get_commande/{id}', [CommandeController::class, 'show']);
Route::put('edit_commande/{id}', [CommandeController::class, 'update']);
Route::delete('delete_commande/{id}', [CommandeController::class, 'destroy']);

Route::get('get_all_paniers', [PanierController::class, 'index']);
Route::post('add_panier', [PanierController::class, 'store']);
Route::get('get_panier/{id}', [PanierController::class, 'show']);
Route::put('edit_panier/{id}', [PanierController::class, 'update']);
Route::delete('delete_panier/{id}', [PanierController::class, 'destroy']);

Route::get('get_all_categories', [CategorieController::class, 'index']);
Route::post('add_categorie', [CategorieController::class, 'store']);
Route::get('get_categorie/{id}', [CategorieController::class, 'show']);
Route::put('edit_categorie/{id}', [CategorieController::class, 'update']);
Route::delete('delete_categorie/{id}', [CategorieController::class, 'destroy']);

Route::get('get_all_menus', [MenuController::class, 'index']);
Route::post('add_menu', [MenuController::class, 'store']);
Route::get('get_menu/{id}', [MenuController::class, 'show']);
Route::put('edit_menu/{id}', [MenuController::class, 'update']);
Route::delete('delete_menu/{id}', [MenuController::class, 'destroy']);

Route::get('get_all_paiements', [PaiementController::class, 'index']);
Route::post('add_paiement', [PaiementController::class, 'store']);
Route::get('get_paiement/{id}', [PaiementController::class, 'show']);
Route::put('edit_paiement/{id}', [PaiementController::class, 'update']);
Route::delete('delete_paiement/{id}', [PaiementController::class, 'destroy']);

