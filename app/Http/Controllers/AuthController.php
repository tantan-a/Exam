<?php

namespace App\Http\Controllers;

use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $statusCode = 500;

        try {
            $email = $request->input('email');
            $password = $request->input('password');

            $user = User::where('email', $email)->first();
            if (!$user) {
                $statusCode = 401;
                throw new Error('Email atau password anda salah !');
            }

            $validatePassword = Hash::check($password, $user->password);
            if (!$validatePassword) {
                $statusCode = 401;
                throw new Error('Email atau password anda salah !');
            }

            $generateToken = bin2hex(random_bytes(40));
            $user->update([
                'token' => $generateToken
            ]);

            return response()->json([
                'meta' => [
                    'code' => 200,
                    'success' => true,
                    'message' => 'Success!'
                ],
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            if ($statusCode != 500) {
                return response()->json([
                    'meta' => [
                        'code' => $statusCode,
                        'success' => false,
                        'message' => $th->getMessage()
                    ]
                ]);
            } else {
                return response()->json([
                    'meta' => [
                        'code' => 500,
                        'success' => false,
                        'message' => 'Internal Server Error!'
                    ]
                ]);
            }
        }
    }
}
