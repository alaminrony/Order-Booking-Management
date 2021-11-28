<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Session;
use App\Models\User;
use Hash;
use Mail;

class LoginController extends Controller {

    public function index(Request $request) {
        $data['title'] = 'Login';
        $data['meta_tag'] = 'Login page,login in rafiq & sons';
        $data['meta_description'] = 'Login page,login in rafiq & sons';
        return view('backEnd.login')->with(compact('data'));
    }

    public function login(Request $request) {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('login')->withInput()->withErrors($validator);
        }

        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (Auth::attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        } else {
            Session::flash('error', 'Credential does not match');
            return redirect()->route('login');
        }
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }

    public function forgetPass() {
        $data['title'] = 'Forget Password';
        $data['meta_tag'] = 'Forget Password page, rafiq & sons';
        $data['meta_description'] = 'Forget Password  rafiq & sons';
        return view('backEnd.forgetPass')->with(compact('data'));
    }

    public function recoveryPassword(Request $request) {

        $rules = [
            'email' => 'required|email',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('forgetPass')->withInput()->withErrors($validator);
        }

        $exisUser = User::where('email', $request->email)->first();
        if (!empty($exisUser)) {
            $newPass = $this->rand_string(8);
            $toEmail = $request->email;
            $toName = $exisUser->name;
            $subject = 'Your recovery password';
            $data = [
                'newPass' => $newPass,
                'toEmail' => $toEmail,
                'toName' => $toName,
                'subject' => $subject,
            ];
            $exisUser->password = Hash::make($newPass);
            $exisUser->save();
            Mail::send('email-template.recover-pass', $data, function($message) use($toEmail, $toName, $subject) {
                $message->to($toEmail, $toName)->subject($subject);
            });
            Session::flash('success', 'Recovery password sent successfully');
            return redirect()->route('forgetPass');
        } else {
            Session::flash('error', 'Email does not exists');
            return redirect()->route('forgetPass');
        }
        return view('backEnd.forgetPass');
    }

    public function rand_string($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }

}
