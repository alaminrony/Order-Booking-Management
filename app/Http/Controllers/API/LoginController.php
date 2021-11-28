<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTAuth;

class LoginController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth:api');
    }


    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(
            ['error' => 'Unauthorized'],
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
            'role_id' => 'required|string',
            'phone' => 'required|string',
        ]);
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password) ;
        $user->role_id = $request->role_id;
        $user->status = $request->status;

        if ($user->save()) {
            $credentials = $request->only('email', 'password');
            if ($token = $this->guard()->setTTL(24*60)->attempt($credentials)) {
                return $this->respondWithToken($token);
            }
        }
        return response()->json(
            ['error' => 'Something went wrong'],
            Response::HTTP_UNAUTHORIZED
        );

    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user(), Response::HTTP_OK);
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
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
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 60 * 24
        ], Response::HTTP_OK);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }


    public function un_authenticate(){

            return response()->json(
                ['error' => 'Unauthorized access!'],
                Response::HTTP_UNAUTHORIZED
            );
    }
}
