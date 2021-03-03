<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PassportController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
        ]);
        if ($validator->fails()){
            return "Failed";
        }

        $user = User::create([
            'name'=> $request->name,
            'email'=>$request->email,
            'password'=> bcrypt($request->password),
        ]);

        $token = $user->createToken('Bearer Token')->accessToken;

        return response()->json([
            'token'=>$token,
            200
        ]);

    }

    public function login(Request $request){
        $cridentials = [
            'email'=>$request->email,
            'password'=>$request->password,
        ];

        if (auth()->attempt($cridentials)){
            $token = auth()->user()->createToken('Bearer Token')->accessToken;
            return response()->json([
                'token'=>$token,
                200
            ]);
        }else{
            return response()->json([
                'error'=>'Unauthorized',401
            ]);
        }
    }

    public function details(){
        return response()->json(["user"=>auth()->user(),200]);
    }
}
