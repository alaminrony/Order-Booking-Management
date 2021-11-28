<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
            [
                'status' => 'Error',
                'message' => 'Invalid email or password',

            ],
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
            if ($token = $this->guard()->attempt($credentials)) {
                return $this->respondWithToken($token);
            }
        }
        return response()->json(
            [
                'status' => 'Error',
                'message' => 'Internal Server Error',
                'error' => [
                    'server_error' => 'Something went wrong'
                ]
            ],
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
        return response()->json([
            'status' => 'Success',
            'message' => 'Data Retrieved Successfully',
            'data' => $this->guard()->user()
        ], Response::HTTP_OK);
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json([ 'status' => 'Success', 'message' => 'Successfully logged out'], Response::HTTP_OK);
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


    public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = $request->user('api')->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Validation Error',
            ], Response::HTTP_NOT_ACCEPTABLE);
        } else {
            if ((Hash::check(request('old_password'), $request->user('api')->password)) == false) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Please enter a password which is not similar then current password.',
                ], Response::HTTP_NOT_ACCEPTABLE);
            } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Please enter a password which is not similar then current password.',
                ], Response::HTTP_NOT_ACCEPTABLE);
            } else {
                User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);

                return response()->json([
                    'status' => 'Success',
                    'message' => 'Password updated successfully.'
                ], Response::HTTP_OK);
            }
        }
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
            'status' => 'Success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'data' => $this->guard()->user()
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
                [
                    'status' => 'Error',
                    'message' => 'Authorization Error',
                    'error' => [
                        'authorization_error' => 'Unauthorized'
                    ]
                ],
                Response::HTTP_UNAUTHORIZED
            );
    }
}
