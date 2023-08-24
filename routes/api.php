<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\tiendasController;
use App\Http\Controllers\ProductsController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('tiendas', [tiendasController::class, 'index']);
Route::post('tiendas', [tiendasController::class, 'store']);
Route::post('tiendas/login', [tiendasController::class, 'login']);
Route::put('tiendas/{id}/update-tienda', [tiendasController::class, 'updateTienda']);
Route::delete('tiendas/{tienda}', [tiendasController::class, 'destroy']);
Route::get('tiendas/{tienda}', [tiendasController::class, 'show']);
Route::get('tiendas/{categoria_id}', [tiendasController::class, 'category']);
Route::get('tiendas/categoria/{categoria}',[tiendasController::class, 'tiendasPorCategoria']);
Route::post('tiendas/{tienda_id}/add-destacadas', [tiendasController::Class, 'addDestacadas']);
Route::get('tiendas/productos-por-tiendas/{tiendaId}',[tiendasController::class, 'mostrarProductosPorTienda']);
Route::post('tiendas/{tienda}/agregar-producto', [tiendasController::class, 'agregarProducto']);
Route::put('tiendas/{tienda}/productos/{producto}',[tiendasController::class, 'actualizarProducto']);
Route::post('tiendas/{id}/agregar-imagen-perfil', [TiendasController::class, 'agregarImagenPerfil']);
Route::put('tiendas/{id}/actualizar-imagen-perfil', [TiendasController::class, 'actualizarImagenPerfil']);
Route::post('tiendas/{id}/agregar-imagen-portada', [TiendasController::class, 'agregarImagenPortada']);
Route::put('tiendas/{id}/actualizar-imagen-portada', [TiendasController::class, 'actualizarImagenPortada']);

Route::post('products', [productsController::class, 'store']);
Route::get('products/{nombre}/mostrarTiendaPorProducto', [productsController::class, 'mostrarTiendaPorProducto']);
Route::put('products/{id}/update-product', [productsController::class, 'updateProducto']);