<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PassportAuthController extends Controller
{
    public $validators = [
        'create' => [
            'name' => 'required|min:4',
            'email' => 'string|max:255|email|unique:users,email,NULL,id,deleted_at,NULL|required',
            'password' => 'required|min:8',
        ]
    ];

    /**
     * Registration
     */
    public function register(Request $request)
    {
        $valid = Validator::make($request->all(), $this->validators['create']);

        if (!$valid->fails()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => app('hash')->make($request->password)
            ]);
            $token = $user->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token]);
        }

        return response()->json(['errors' => $valid->errors()], 400);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
