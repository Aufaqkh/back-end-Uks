<?php

namespace App\Http\Controllers; // 💡 Perhatiin, ini gak pake \Api biar bener kamarnya

use App\Models\User;
use App\Models\Pasien;
use App\Models\PetugasUks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'nisn'     => 'required|string|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string|in:admin,petugas,siswa,pasien',
            'kelas'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // 💡 INI OBATNYA: Bikin email palsu biar database seneng
            $dummyEmail = $request->nisn . '@pasien.uks.com';

            $user = User::create([
                'name'     => $request->name,
                'email'    => $dummyEmail,
                'nisn'     => $request->nisn,
                'kelas'    => $request->kelas ?? '-',
                'role'     => $request->role,
                'password' => Hash::make($request->password),
            ]);

            if (in_array($request->role, ['pasien', 'siswa'])) {
                Pasien::create([
                    'user_id'      => $user->id,
                    'nisn'         => $request->nisn,
                    'nama_lengkap' => $request->name,
                    'kelas'        => $request->kelas ?? '-',
                ]);
            } elseif (in_array($request->role, ['petugas', 'admin'])) {
                PetugasUks::create([
                    'user_id'      => $user->id,
                    'nama_lengkap' => $request->name,
                    'nip'          => $request->nip ?? 'NIP-' . $user->id,
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status'  => 'success',
                'message' => 'Akun berhasil dibuat bray!',
                'user'    => $user,
                'token'   => $token
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Database nolak nyimpen data bray! Cek error aslinya nih:',
                'errors'  => ['database' => [$e->getMessage()]]
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('nisn', $request->nisn)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'NISN atau password salah bray!'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Login berhasil!',
            'user'    => $user,
            'token'   => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Berhasil Logout bray!']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
