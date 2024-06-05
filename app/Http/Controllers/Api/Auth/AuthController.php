<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use JsonResponseTrait;

    public function register(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255|min:8'
        ]);

        if ($validate->fails()) {
            return $this->jsonResponse([], 422, $validate->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return $this->jsonResponse(['usuario' => $user], 200, []);
    }

    public function login(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return $this->jsonResponse([], 422, $validate->errors());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->jsonResponse([], 404, ['message' => 'Usuario no encontrado']);
        }

        if (Hash::check($request->password, $user->password)) {

            $request->request->add([
                'grant_type' => 'password',
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'username' => $request->email,
                'password' => $request->password,
            ]);

            $proxy = Request::create(
                'oauth/token',
                'POST'
            );

            $respuesta = Route::dispatch($proxy);
            $response = json_decode($respuesta->getContent());

            return $this->jsonResponse($response, 200, []);
        } else {
            return $this->jsonResponse([], 200, ['message' => 'Las credenciales no coinciden']);
        }
    }

    public function user(Request $request)
    {

        return $this->jsonResponse($request->user(), 200, []);
    }

    public function logout(Request $request)
    {

        $user = $request->user();

        $user->token()->revoke();

        return $this->jsonResponse(['message' => 'Sesion cerrada correctamente'], 200, []);
    }
}
