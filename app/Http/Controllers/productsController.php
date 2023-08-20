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

    public function store(Request $request)
    {
        $request->validate([
            'tienda_id' => 'required|exists:tiendas,id',
            'codigo' => 'required|string',
            'nombre' => 'required|string',
            'precio' => 'required|min:3',
            'cantidad' => 'required|integer|min:1',
            'imagen' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

         // Crear el registro de los productos en la base de datos
         $products= new products([
            'tienda_id' => $request->tienda_id,
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'precio'=>$request->precio,
            'cantidad' => $request->cantidad,
            'imagen'=>$request->imagen,
            
        ]);
         //$products = products::create($request->all());
         $imagen= $request->file('imagen')->store('public/images');
         $imagenUrl = Storage::url($imagen);
         $products->imagen = $imagenUrl;
         $products->save();
         return response()->json(['message' =>' producto guardado exitosamente'], 201);

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
  //no funciona
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

    // Actualizar los campos del producto
    
    $product->codigo = $request->codigo;
    $product->nombre = $request->nombre;
    $product->precio = $request->precio;
    $product->cantidad = $request->cantidad;

    if ($request->hasFile('imagen')) {
        // Eliminar la imagen anterior si existe
        Storage::delete($product->imagen);

        // Subir la nueva imagen
        $imagen = $request->file('imagen')->store('public/images');
        $imagenUrl = Storage::url($imagen);
        $product->imagen = $imagenUrl;
    }
    $product->save();
    return response()->json(['message' => 'Producto actualizado exitosamente'], 200);
}
 
}