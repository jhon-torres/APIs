<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'correo' => 'required|string|email',
            'contraseña' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Completa los campos correctamente'], 422);
        } else {
            $user = User::where('correo', $request->input("correo"))->first();

            if ($user) {
                if (Hash::check($request['contraseña'], $user->contraseña)){
                    $token = $user->createToken('auth_token')->plainTextToken;
                    return response()->json(['token' => $token], 200);
                } else {
                    return response()->json(['error' => 'Credenciales incorrectas'], 401);
                }
            } else{
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'Has cerrado sesión exitosamente'
        ];
    }
}
