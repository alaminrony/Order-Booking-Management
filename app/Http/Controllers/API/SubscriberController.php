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
use Mail;


class SubscriberController extends Controller
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

    public function registration(Request $request)
    {
        
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $randPassword = $this->randomPassword();
        // echo "<pre>";print_r($randPassword);exit;
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($randPassword) ;
        $user->role_id = '8';
        $user->status = '1';

        if ($user->save()) {

            $newPass = $randPassword;
            $toEmail = $user->email;
            $toName = $user->name;
            $subject = 'Your Login password';
            $data = [
                'newPass' => $newPass,
                'toEmail' => $toEmail,
                'toName' => $toName,
                'subject' => $subject,
            ];


            Mail::send('email-template.subscriber', $data, function($message) use($toEmail, $toName, $subject) {
                $message->to($toEmail, $toName)->subject($subject);
            });

            return response()->json([
                'status' => 'Success',
                'message' => 'An email with new password has been sent to your email.',
            ], Response::HTTP_OK);


            // $credentials = $request->only('email', 'password');
            // if ($token = $this->guard()->attempt($credentials)) {
            //     return $this->respondWithToken($token);
            // }
        }
    }

    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    public function forgetPassword(Request $request){
        $user = User::where('email',$request->email)->first();
        
        $randPassword = $this->randomPassword();
        if(!empty($user)){
            $user->password = Hash::make($randPassword);
            $user->save();

            $newPass = $randPassword;
            $toEmail = $user->email;
            $toName = $user->name;
            $subject = 'Your Recovery password';
            $data = [
                'newPass' => $newPass,
                'toEmail' => $toEmail,
                'toName' => $toName,
                'subject' => $subject,
            ];


            Mail::send('email-template.sub-forget-pass', $data, function($message) use($toEmail, $toName, $subject) {
                $message->to($toEmail, $toName)->subject($subject);
            });

            return response()->json([
                'status' => 'Success',
                'message' => 'An email with new password has been sent to your email.',
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Email does not exists',
            ]);
        }
       
    }

   
    

    
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
