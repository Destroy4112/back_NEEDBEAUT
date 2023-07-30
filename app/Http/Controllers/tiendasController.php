<?php

namespace App\Http\Controllers;

use App\Models\tiendas;
use App\Models\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'perfil' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'portada' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Procesar la imagen (si se cargó una)
        $imagen = $request->file('imagen');
        $imagenPath = null;
        if ($imagen) {
            $imagenPath = $imagen->store('images', 'public');
        }

        // Crear el registro de la tienda en la base de datos
        $tienda = new tiendas([
            'propietario' => $request->propietario,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'negocio' => $request->negocio,
            'categoria' => $request->categoria,
            'nit' => $request->nit,
            'ubicacion' => $request->ubicacion,
            'telefono' => $request->telefono,
            'perfil' => $request->perfil,
            'portada' => $request->portada,
        ]);
        $tienda->save();

        // Crear el registro de la imagen en la tabla de imágenes
        if ($imagenPath) {
            $Image = new images([
                'tienda_id' => $tienda->id,
                // Asociar con la tienda recién creada
                'image_path' => $imagenPath,
            ]);
            $Image->save();
        }

        return response()->json(['message' => 'Tienda almacenada correctamente'], 201);
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
                    "message" => "usuario ingresado correctamente",
                ]);
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "password incorrecta",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 0,
                "message" => "Usuario no Registrado",
            ], 404);
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
            'nombreP' => 'required|string',
            'cedula' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string',
            'nombreN' => 'required|string',
            'registro' => 'required|string',
            'ubicacion' => 'required|string',
            'telefono' => 'required|string',
            'imagen' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

        // Obtener el registro de tienda existente por su ID
        $tienda = tiendas::findOrFail($id);

        // Procesar la imagen (si se cargó una)
        $imagen = $request->file('imagen');
        if ($imagen) {
            $imagenPath = $imagen->store('images', 'public');
            $tienda->imagen = $imagenPath;
        }

        // Actualizar los campos del registro
        $tienda->nombreP = $request->nombreP;
        $tienda->cedula = $request->cedula;
        $tienda->email = $request->email;
        $tienda->password = Hash::make($request->password); // Actualizar la contraseña con el nuevo hash
        $tienda->nombreN = $request->nombreN;
        $tienda->registro = $request->registro;
        $tienda->ubicacion = $request->ubicacion;
        $tienda->telefono = $request->telefono;


        // Guardar los cambios
        $tienda->save();

        return response()->json(['message' => 'Registro actualizado correctamente'], 200);
    }
}
