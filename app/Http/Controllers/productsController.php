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
            'codigo' => 'required|string',
            'nombre' => 'required|string',
            'precio' => 'required|integer',
            'cantidad' => 'required|integer',
        ]);
         // Crear el registro de los productos en la base de datos
         $products = products::create($request->all());
         return response()->json(['data' => $products], 201);

    }
}