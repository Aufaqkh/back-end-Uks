<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pasien;
use App\Models\PetugasUks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // 🔥 Wajib ditambah buat pengaman database
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'nisn'     => 'required|string|unique:users',
            // Pastiin Vue ngirim input "password_confirmation" juga ya bre!
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string|in:admin,petugas,siswa,pasien',
            'kelas'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 🔥 Mulai sistem pengaman: Kalau ada error di tengah jalan, batalin semua!
        DB::beginTransaction();

        try {
            $dummyEmail = $request->nisn . '@pasien.uks.com';

            $user = User::create([
                'name'     => $request->name,
                'email'    => $dummyEmail,
                'nisn'     => $request->nisn,
                'kelas'    => $request->kelas ?? '-',
                'role'     => $request->role,
                'password' => Hash::make($request->password),
            ]);

            // Simpan Profil ke tabel Pasien / Petugas
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

            // 🔥 Semua aman? Kunci dan simpan datanya bray!
            DB::commit();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status'  => 'success',
                'message' => 'Akun berhasil dibuat bray!',
                'user'    => $user,
                'token'   => $token
            ], 201);

        } catch (Exception $e) {
            // 🔥 Kalau gagal, rollback (hapus data setengah mateng tadi)
            DB::rollBack();

            return response()->json([
                'message' => 'Database nolak nyimpen data bray!',
                'errors'  => ['database' => [$e->getMessage()]]
            ], 500);
        }
    }

    public function login(Request $request)
    {
        // 🔥 Ubah ke Validator::make biar 100% nge-return JSON pas error
        $validator = Validator::make($request->all(), [
            'nisn'     => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

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
