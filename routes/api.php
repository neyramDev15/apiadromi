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

Route::get('users', [UserController::class, 'index']);
Route::post('users', [UserController::class, 'store']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);

Route::get('admins', [AdminController::class, 'index']);
Route::post('admins', [AdminController::class, 'store']);
Route::get('admins/{id}', [AdminController::class, 'show']);
Route::put('admins/{id}', [AdminController::class, 'update']);
Route::delete('admins/{id}', [AdminController::class, 'destroy']);


Route::get('panier/{id}/menus', [PanierMenuController::class, 'index']);
Route::post('panier/menu', [PanierMenuController::class, 'store']);
Route::put('panier/menu/{id}', [PanierMenuController::class, 'update']);
Route::delete('panier/menu/{id}', [PanierMenuController::class, 'destroy']);


Route::get('commande/{id}/menus', [CommandeMenuController::class, 'index']);
Route::post('commande/menu', [CommandeMenuController::class, 'store']);
Route::put('commande/menu/{id}', [CommandeMenuController::class, 'update']);
Route::delete('commande/menu/{id}', [CommandeMenuController::class, 'destroy']);

Route::get('commandes', [CommandeController::class, 'index']);
Route::post('commandes', [CommandeController::class, 'store']);
Route::get('commandes/{id}', [CommandeController::class, 'show']);
Route::put('commandes/{id}', [CommandeController::class, 'update']);
Route::delete('commandes/{id}', [CommandeController::class, 'destroy']);




Route::get('paniers', [PanierController::class, 'index']);
Route::post('paniers', [PanierController::class, 'store']);
Route::get('paniers/{id}', [PanierController::class, 'show']);
Route::put('paniers/{id}', [PanierController::class, 'update']);
Route::delete('paniers/{id}', [PanierController::class, 'destroy']);

Route::get('categories', [CategorieController::class, 'index']);
Route::post('categories', [CategorieController::class, 'store']);
Route::get('categories/{id}', [CategorieController::class, 'show']);
Route::put('categories/{id}', [CategorieController::class, 'update']);
Route::delete('categories/{id}', [CategorieController::class, 'destroy']);

Route::get('menus', [MenuController::class, 'index']);
Route::post('menus', [MenuController::class, 'store']);     
route::get('menus/{id}', [MenuController::class, 'show']);
Route::put('menus/{id}', [MenuController::class, 'update']);
Route::delete('menus/{id}', [MenuController::class, 'destroy']);

route::get('paiements', [PaiementController::class, 'index']);
route::post('paiements', [PaiementController::class, 'store']); 
route::get('paiements/{id}', [PaiementController::class, 'show']);
route::put('paiements/{id}', [PaiementController::class, 'update']);        
route::delete('paiements/{id}', [PaiementController::class, 'destroy']);


Route::apiResource('categories', CategorieController::class);
Route::apiResource('menus', MenuController::class);
Route::apiResource('commandes', CommandeController::class);
Route::apiResource('paiements', PaiementController::class);
Route::apiResource('admins', AdminController::class);
Route::apiResource('users', ClientController::class);
Route::apiResource('paniers', PanierController::class);
route::apiResource('paiements', PaiementController::class);
Route::apiResource('panier-menu', PanierMenuController::class)->only(['store','update','destroy','index']);
Route::apiResource('commande-menu', CommandeMenuController::class)->only(['store','update','destroy','index']);



