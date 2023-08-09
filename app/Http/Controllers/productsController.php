<?php

namespace App\Http\Controllers;

use App\Models\products;
use Illuminate\Http\Request;


class productsController extends Controller
{
    public function index()
    {
        $products = Products::all();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tienda_id' => 'required|exists:tiendas,id',
            'codigo' => 'required|string',
            'nombre' => 'required|string',
            'precio' => 'required|min:3',
            'cantidad' => 'required|integer|min:1',
        ]);
         // Crear el registro de los productos en la base de datos
         $products = products::create($request->all());
         return response()->json(['message' =>' producto guardado exitosamente'], 201);

    }
   
}