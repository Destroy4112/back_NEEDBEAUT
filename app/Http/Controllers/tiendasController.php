<?php

namespace App\Http\Controllers;

use App\Models\tiendas;
use App\Models\images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;



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
            'slogan'=> 'required|string',
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

    public function imagenPerfil(Request $request, Tiendas $tienda)
    {
        $request->validate([
            'perfil' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('perfil')) {
            $perfilPath = $request->file('perfil')->store('public/images');
            $perfilUrl = Storage::url($perfilPath);
            $tienda->perfil = $perfilUrl;
            $tienda->save();
        }
        return response()->json(['succes' => 'Imagen de perfil subida exitosamente.'], 201);
    }
    public function imagenPortada(Request $request, Tiendas $tienda)
    {
        $request->validate([
            'portada' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('portada')) {
            $portadaPath = $request->file('portada')->store('public/images');
            $portadaUrl = Storage::url($portadaPath);
            $tienda->portada = $portadaUrl;
            $tienda->save();
        }
        return response()->json(['succes' => 'Imagen de portada subida exitosamente.'], 201);

    }

    //no funciona
    public function actualizarImagenPerfil(Request $request, $id) {
        $request->validate([
            'perfil' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $tienda = Tiendas::find($id);
        if ($tienda) {
            if ($request->hasFile('perfil')) {
                Storage::delete($tienda->perfil);
                $nuevaImagen = $request->file('perfil')->store('public/images');
                $perfilUrl = Storage::url($nuevaImagen);
                $tienda->perfil = $perfilUrl;
                $tienda->save();
                return response()->json(['mensaje' => 'Imagen de perfil actualizada correctamente']);
            } else {
                return response()->json(['error' => 'No se proporcionó una nueva imagen'], 400);
            }
        } else {
            return response()->json(['error' => 'Tienda no encontrada'], 404);
        }
    }
 
 
    public function addDestacadas(Request $request, $tienda_id)
    {
        $tienda = tiendas::findOrFail($tienda_id);
        $request->validate([
            'destacadas' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $destacadaPath = $request->file('destacadas')->store('public/images');
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
        $tienda->email=$request->email;
        $tienda->categoria = $request->categoria;
        $tienda->negocio= $request->negocio;
        $tienda->slogan= $request->slogan;
        $tienda->nit = $request->nit;
        $tienda->ubicacion= $request->ubicacion;
        $tienda->telefono = $request->telefono;
        
        // Si se proporcionó una nueva contraseña, actualízala
        if ($request->filled('password')) {
            $tienda->password = Hash::make($request->password);
        }
        // Guarda los cambios en la base de datos
        $tienda->save();

        return response()->json(['message' => 'Tienda actualizada correctamente'], 200);
    }

  

}