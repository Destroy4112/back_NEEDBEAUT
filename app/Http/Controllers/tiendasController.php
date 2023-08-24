<?php

namespace App\Http\Controllers;

use App\Models\tiendas;
use App\Models\images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Products;



/**
 * Summary of tiendasController
 */
class tiendasController extends Controller
{

    public function index()
    {
        $tienda = Tiendas::all();

        return response()->json($tienda);
    }
    public function store(Request $request)
    {

        // Validar los datos del formulario
        $request->validate([
            'propietario' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string',
            'negocio' => 'required|string',
            'slogan' => 'required|string',
            'categoria' => 'required|string',
            'nit' => 'required|string',
            'ubicacion' => 'required|string',
            'telefono' => 'required|string',

        ]);

        $categoria = strtolower($request->categoria);
        // Crear el registro de la tienda en la base de datos
        $tienda = new tiendas([
            'propietario' => $request->propietario,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'negocio' => $request->negocio,
            'slogan' => $request->slogan,
            'categoria' => $categoria,
            'nit' => $request->nit,
            'ubicacion' => $request->ubicacion,
            'telefono' => $request->telefono,
        ]);
        $tienda->save();
        return response()->json(['data' => $tienda], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $tienda = tiendas::where("email", "=", $request->email)->first();
        if (!is_null($tienda)) {
            if (Hash::check($request->password, $tienda->password)) {
                return response()->json([
                    "status" => 1,
                    "data" => $tienda
                ]);

            } else {
                return response()->json([
                    "status" => 2,
                    "message" => "password incorrecta",
                ], 200);
            }
        } else {
            return response()->json([
                "status" => 0,
                "message" => "Usuario no Registrado",
            ], 200);
        }
    }


    public function destroy(tiendas $tienda)
    {
        $tienda->delete();
        $data = [
            'message' => 'tienda borrada correctamente',
            'tienda' => $tienda
        ];
        return response()->json($data);
    }

    public function show(tiendas $tienda)
    {
        return response()->json($tienda);
    }

    public function addDestacadas(Request $request, $tienda_id)
    {
        $tienda = tiendas::findOrFail($tienda_id);
        $request->validate([
            'destacadas' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $nombreOriginal = $request->file('destacadas')->getClientOriginalName();
        $destacadaPath = $request->file('destacadas')->storeAs('public/images',$nombreOriginal);
        $destacadaUrl = Storage::url($destacadaPath);
        Images::create([
            'tienda_id' => $tienda->id,
            'destacadas' => $destacadaUrl,

        ]);
        return response()->json(['succes' => 'Imagen destacada subida exitosamente.'], 201);
    }

    public function tiendasPorCategoria($categoria)
    {
        $tiendas = Tiendas::where('categoria', $categoria)->get();
        if ($tiendas->isEmpty()) {
            return response()->json(['message' => 'No se encontraron tiendas para esta categoría.'], 404);
        }
        return response()->json($tiendas);
    }

    public function mostrarProductosPorTienda($tiendaId)
    {
        $tienda = Tiendas::findOrFail($tiendaId);
        $productos = $tienda->products->map(function ($producto) {
            return [
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
            ];
        });

        return response()->json($productos);
    }

    public function updateTienda(Request $request, $id)
    {
        $request->validate([
            'propietario' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string',
            'negocio' => 'required|string',
            'slogan' => 'required|string',
            'categoria' => 'required|string|in:moda, belleza',
            'nit' => 'required|string',
            'ubicacion' => 'required|string',
            'telefono' => 'required|string',

        ]);
        // Obtener el registro de tienda existente por su ID
        $tienda = tiendas::findOrFail($id);
        // Actualiza los campos
        $tienda->propietario = $request->propietario;
        $tienda->email = $request->email;
        $tienda->categoria = $request->categoria;
        $tienda->negocio = $request->negocio;
        $tienda->slogan = $request->slogan;
        $tienda->nit = $request->nit;
        $tienda->ubicacion = $request->ubicacion;
        $tienda->telefono = $request->telefono;

        // Si se proporcionó una nueva contraseña, actualízala
        if ($request->filled('password')) {
            $tienda->password = Hash::make($request->password);
        }
        // Guarda los cambios en la base de datos
        $tienda->save();

        return response()->json(['message' => 'Tienda actualizada correctamente'], 200);
    }
    public function addImagenPerfil(Request $request, $id) {
        $tienda = Tiendas::find($id);
    
        if (!$tienda) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);
        }
    
        if ($request->hasFile('perfil')) {
       
            $nombreOriginal = $request->file('perfil')->getClientOriginalName();
                // Subir la nueva imagen
                $imagen = $request->file('perfil')->storeAs('public/images', $nombreOriginal);
                $imagenUrl = Storage::url($imagen);
                 // Eliminar la ruta de la imagen anterior si existe
            if ($tienda->perfil && $tienda->perfil != $imagenUrl) {
                Storage::disk('local')->delete(str_replace('/storage', 'public', $tienda->perfil));
            }
                $tienda->perfil = $imagenUrl;
                $tienda->save();
                return response()->json(['message' => 'Imagen de perfil subida exitosamente'], 200);
            }
            return response()->json(['message' => 'imagen no cargada'], 400);   
    }

    public function addImagenPortada(Request $request, $id) {
        $tienda = Tiendas::find($id);
    
        if (!$tienda) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);
        }
    
        if ($request->hasFile('portada')) {
       
            $nombreOriginal = $request->file('portada')->getClientOriginalName();
                // Subir la nueva imagen
                $imagen = $request->file('portada')->storeAs('public/images', $nombreOriginal);
                $imagenUrl = Storage::url($imagen);
                 // Eliminar la ruta de la imagen anterior si existe
            if ($tienda->portada && $tienda->portada != $imagenUrl) {
                Storage::disk('local')->delete(str_replace('/storage', 'public', $tienda->portada));
            }
                $tienda->portada = $imagenUrl;
                $tienda->save();
                return response()->json(['message' => 'Imagen de portada subida exitosamente'], 200);
            }
            return response()->json(['message' => 'imagen no cargada'], 400);   
    }
    
}