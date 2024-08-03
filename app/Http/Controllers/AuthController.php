<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        //leer parametros
        $credentials = request(['login', 'password']);

        //valida que exsistan
        if(!isset($credentials['login']) OR !isset($credentials['password'])){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        //valida que login no sea null o ""
        if(!$credentials['login']){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        //si es un email se selecciona la columna email si no la columna short name
        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'short_name';

        //Se añade que la cuenta a lagearse tenga el valor 1 (true) en el campos active
        $loginCondition = [$fieldType => $credentials['login'], 'password' => $credentials['password'],'active' => 1];

        if ($fieldType == 'email') {
            $loginCondition['id_perfil'] = [5,6]; // Si es email el perfil tiene que ser empresa o familia
        }
        //else{
        //    $loginCondition['id_perfil'] = [1,2,3,4]; // para short name la cuenta es interna
        //}

        // se valida la cuenta en caso de true retorna token
        if (! $token = auth()->attempt(
            $loginCondition
            )) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        $user->perfil;
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        $user = auth()->user();
        $user->perfil;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 240,
            'data' => $user,
        ]);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => ['required','email:rfc','max:100','unique:users','regex:/^\S*$/u'],
            'password' => 'required|string|min:6|confirmed',
            'id_perfil' => 'required|int',
            'id_cliente' => 'nullable|int',
        ]);


        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }



        $user = User::create(array_merge(
            $validator->validate(),
            [
                'password' => bcrypt($request->password)
            ]
        ));

        return response()->json(['message' => 'Nuevo usuario creado', 'data' => $user], 201);
    }
}
