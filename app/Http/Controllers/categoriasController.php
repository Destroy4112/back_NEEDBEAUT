<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categorias;
class categoriasController extends Controller
{
    //
    public function tiendasPorCategoria(Categorias $categoria)
    {
        $tiendas = $categoria->tiendas;
        if ($tiendas->isEmpty()) {
            return response()->json(['message' => 'No se encontraron tiendas para esta categoría.'], 404);
        }
        return response()->json($tiendas);
    }
}

