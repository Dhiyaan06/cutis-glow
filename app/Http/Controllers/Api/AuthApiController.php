<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        // 1. Buat User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'role' => 'pasien',
            'status_aktif' => 'aktif',
        ]);

        // Assign Role
        $role = Role::findOrCreate('pasien');
        $user->assignRole($role);

        // 2. Buat Pasien Profile
        Pasien::create([
            'id_pengguna' => $user->id_pengguna,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        $token = $user->createToken('flutter-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'user' => [
                'id_pengguna' => $user->id_pengguna,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'pasien',
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kredensial yang diberikan salah.'
            ], 401);
        }

        $token = $user->createToken('flutter-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil',
            'token' => $token,
            'user' => [
                'id_pengguna' => $user->id_pengguna,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->getRoleNames()->first() ?? $user->role,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil logout'
        ]);
    }
}
