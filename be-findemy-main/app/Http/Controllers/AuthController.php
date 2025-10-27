<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $baseUsername = strtolower(preg_replace('/\s+/', '', $request->name)); // hapus spasi dan jadikan huruf kecil
        $username = $baseUsername;

        $counter = 1;
        while (\App\Models\User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'name'     => $request->name,
            'username' => $username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Register success',
            'user'    => $user,
            'token'   => $token,
        ]);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    // 🔹 LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    // 🔹 ME (USER LOGIN SAAT INI)
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $code = random_int(1000, 9999);
        $email = $request->email;

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $code, 'created_at' => now()]
        );

        Mail::raw("Kode reset password Anda adalah: $code", function ($message) use ($email) {
            $message->to($email)
                ->subject('Kode Reset Password');
        });

        return response()->json([
            'meta' => [
                'status_code' => 200,
                'status' => 'success',
                'message' => 'Kode verifikasi telah dikirim ke email.'
            ]
        ], 200);
    }


    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:4',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->code)
            ->first();

        if (!$record) {
            return response()->json([
                'meta' => [
                    'status_code' => 400,
                    'status' => 'error',
                    'message' => 'Kode tidak valid.'
                ]
            ], 400);
        }

        return response()->json([
            'meta' => [
                'status_code' => 200,
                'status' => 'success',
                'message' => 'Kode valid, lanjutkan ke reset password.'
            ]
        ], 200);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:4',
            'password' => 'required|string|min:8',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->code)
            ->first();

        if (!$record) {
            return response()->json([
                'meta' => [
                    'status_code' => 400,
                    'status' => 'error',
                    'message' => 'Kode tidak valid.'
                ]
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'meta' => [
                'status_code' => 200,
                'status' => 'success',
                'message' => 'Password berhasil direset.'
            ]
        ], 200);
    }
}
