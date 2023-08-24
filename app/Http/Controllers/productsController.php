<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\tiendas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class productsController extends Controller
{
    public function index()
    {
        $products = Products::all();

        return response()->json($products);
    }
public function store (Request $request){
    $request->validate([
        'tienda_id'=>'required|exists:tiendas,id',
        'codigo' => 'required|string',
        'nombre' => 'required|string',
        'precio' => 'required|numeric',
        'cantidad' => 'required|integer',
        'imagen' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        
    ]);

    $producto = new products([
        'tienda_id'=>$request->tienda_id,
        'codigo' => $request->codigo,
        'nombre' => $request->nombre,
        'precio' => $request->precio,
        'cantidad' => $request->cantidad,
        
    ]);
    if ($request->hasFile('imagen')) {
        $nombreOriginal = $request->file('imagen')->getClientOriginalName();
        $imagen = $request->file('imagen')->storeAs('public/images', $nombreOriginal);
        $producto->imagen = Storage::url($imagen);
    }
    $producto->save();

    return response()->json(['mensaje' => 'Producto agregado a la tienda']);
}

    public function mostrarTiendaPorProducto($nombre)
    {
        $producto = Products::where('nombre', $nombre)->get();
        if(!$producto){
            return response()->json(['error'=>'producto no encontrado'], 404);
        }
        $tiendasconProductos = Tiendas::whereHas('products', function ($query) use ($nombre) {
            $query->where('nombre', $nombre);
        })->get();
        $tiendasInfo = $tiendasconProductos->map(function ($tienda) {
            return [
                'nit' => $tienda->nit,
                'negocio' => $tienda->negocio,
                'ubicacion' => $tienda->ubicacion,
                'telefono' => $tienda->telefono,
                'email'=> $tienda->email,
            ];
        });
        return response()->json($tiendasInfo);
    }
    public function updateProducto(Request $request, $id)
{
    $request->validate([
      
        'codigo' => 'required|string',
        'nombre' => 'required|string',
        'precio' => 'required|min:3',
        'cantidad' => 'required|integer|min:1',
        'imagen' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $product = Products::findOrFail($id);

    $product->codigo = $request->codigo;
    $product->nombre = $request->nombre;
    $product->precio = $request->precio;
    $product->cantidad = $request->cantidad;

    if ($request->hasFile('imagen')) {
       
    $nombreOriginal = $request->file('imagen')->getClientOriginalName();
        // Subir la nueva imagen
        $imagen = $request->file('imagen')->storeAs('public/images', $nombreOriginal);
        $imagenUrl = Storage::url($imagen);
         // Eliminar la ruta de la imagen anterior si existe
    if ($product->imagen && $product->imagen != $imagenUrl) {
        Storage::disk('local')->delete(str_replace('/storage', 'public', $product->imagen));
    }
        $product->imagen = $imagenUrl;
        
    }
    $product->save();
    return response()->json(['message' => 'Producto actualizado exitosamente'], 200);
}
 
}