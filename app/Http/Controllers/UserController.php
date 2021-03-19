<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','refresh']]);
    }

    

    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ], [
            'username.required' => 'Username harus diisi',
            'username.min' => 'Minimal 3 karakter',
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        $input = $request->only('username', 'email', 'password');

        try {
            $user = new User;
            $user->username = $input['username'];
            $user->email = $input['email'];
            $user->password = app('hash')->make($input['password']);

            //save user
            if ($user->save()) {
                $code = 200;
                $output = [
                    'user' => $user,
                    'code' => $code,
                    'massage' => 'User created successfully.'
                ];
            } else {
                $code = 500;
                $output = [
                    'user' => $user,
                    'code' => $code,
                    'massage' => 'An error occured while creating user.'
                ];
            }
        } catch (\Exception $e) {
            $code = 500;
            $output = [
                'code' => $code,
                'massage' => 'An error occured while creating user.'
            ];
        }
        //end register user

        //return
        return response()->json($output, $code);
    }


    public function messages()
    {
        return [
            'username.required' => 'A title is required',
            'password.required' => 'A message is required',
        ];
    }

    public function login(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'username' => 'required|min:3',
            'password' => 'required|string|min:6'
        ], [
            'username.required' => 'Username harus diisi',
            'username.min' => 'Minimal 3 karakter',
            'password.required' => 'Password harus diisi',
        ]);
   

        $input = $request->only('username', 'password');
       
        if (!$authorized = Auth::attempt($input)) {
            
            $code = 401;
            $output = [
                'code' => $code,
                'message' => 'User is not authorized.'
            ];
        } else {
            $code = 201;
            $data_user = $this->guard()->user();
            
            $user = [
                "username"                  => $data_user['username'],
                "email"                     => $data_user['email'],
                ];
            $token = $this->responseWithToken($authorized,$user);
            
            $output = array_merge(['status' => 'success','message' => 'Berhasil Masuk'],['data' =>$token]);
        }

        return response()->json($output, $code);
    }

    //untuk melihat detail data user tanpa melihat token
    public function user()
    {
        return $this->response_json($this->guard()->user());
    }

    public function refresh()
    {
       
        $token = $this->responseWithToken(Auth::refresh(),'');
        return $this->response_json($token);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged Out!']);
    }
    //biar fungsi guard gk error
    public function guard()
    {
        return Auth::guard();
    }
}
