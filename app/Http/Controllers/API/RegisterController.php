<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {

        // return response()->json($request->input('contraseña'));

        // return response()->json($request);
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:users',
            'numeroTlf' => 'required|string|max:10',
            'direccion' => 'required|string|max:255',
            'contraseña' => 'required|string|min:8',
        ]);

        // variable boolean para verificar que la contraseña coincide con la confirmacion
        $correcta_confirmacion = $request->input('contraseña') == $request->input('confirmacion_contraseña');

        // Si la validación falla, devuelve un mensaje de error
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            if (!$correcta_confirmacion) {
                return response()->json(['error' => 'Contraseña no coincide con la confirmación'], 422);
            }

            // Crear el nuevo usuario
            $user = User::create([
                'nombres' => $request->input('nombres'),
                'apellidos' => $request->input('apellidos'),
                'correo' => $request->input('correo'),
                'numeroTlf' => $request->input('numeroTlf'),
                'direccion' => $request->input('direccion'),
                'contraseña' => Hash::make($request->input('contraseña')),
            ]);

            // Retornar una respuesta exitosa
            return response()->json(['message' => 'Usuario registrado exitosamente'], 201);
        }
    }
}
