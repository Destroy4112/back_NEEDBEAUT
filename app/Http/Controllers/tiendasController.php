<?php

namespace App\Http\Controllers;

use App\Models\tiendas;
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
        'categoria' => $categoria,
        'nit' => $request->nit,
        'ubicacion' => $request->ubicacion,
        'telefono' => $request->telefono,
       
         
    ]);
    $tienda->save();
        return response()->json(['data' => $tienda], 201);
    }

    public function imagenPerfil(Request $request, Tiendas $tienda){
        $request->validate([
            'perfil'=> 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('perfil')) {
            $perfilPath = $request->file('perfil')->store('public/images');
            $perfilUrl = Storage::url($perfilPath);
            $tienda->perfil = $perfilUrl;
            $tienda->save();
        }
        return response()->json(['succes' => 'Imagen de perfil subida exitosamente.'], 201);
       
    }

    public function imagenPortada(Request $request, Tiendas $tienda){
        $request->validate([
            'portada'=> 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('portada')) {
            $portadaPath = $request->file('portada')->store('public/images');
            $portadaUrl = Storage::url($portadaPath);
            $tienda->portada = $portadaUrl;
            $tienda->save();
        }
        return response()->json(['succes' => 'Imagen de portada subida exitosamente.'], 201);
       
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
        //
        $tienda->delete();
        $data = [
            'message' => 'tienda borrada correctamente',
            'tienda' => $tienda
        ];
        return response()->json($data);
    }

    public function show(tiendas $tienda)
    {
        //
        return response()->json($tienda);
    }

    //falta probar funcionalidad de update
    public function update(Request $request, $id)
    {
        $request->validate([
            'propietario' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string',
            'negocio' => 'required|string',
            'categoria' => 'required|string',
            'nit' => 'required|string',
            'ubicacion' => 'required|string',
            'telefono' => 'required|string',
            'perfil' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'portada' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

        // Obtener el registro de tienda existente por su ID
        $tienda = tiendas::findOrFail($id);

        // Procesar la imagen (si se cargó una)
        $imagen = $request->file('imagen');
        if ($imagen) {
            $imagenPath = $imagen->store('images', 'public');
            $tienda->imagen = $imagenPath;
        }

         $tienda = Tiendas::create($request->all());


        return response()->json(['message' => 'Registro actualizado correctamente'], 200);
    }



    
}
