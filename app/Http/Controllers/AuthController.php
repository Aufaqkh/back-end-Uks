<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pasien;
use App\Models\PetugasUks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
$validator = Validator::make($request->all(), [
    'name'     => 'required|string|max:255',
    // Kita buat emailnya wajib berformat email asli (.com/.id dll) bray
    'email'    => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:8|confirmed',
    'role'     => 'required|string|in:admin,petugas,siswa',
    'nisn'     => 'nullable|string',

    // 💡 TAKTIK JITU: Kolom kelas HANYA required kalau rolenya 'siswa'.
    // Kalau rolenya 'petugas' atau 'admin', dia berubah jadi 'nullable' (boleh null/kosong)!
    'kelas'    => $request->role === 'siswa' ? 'required|string' : 'nullable|string',
]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nisn' => $request->nisn,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role === 'pasien') {
            Pasien::create([
                'user_id' => $user->id,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->name,
                'kelas' => $request->kelas,
            ]);
        } elseif (in_array($request->role, ['petugas', 'admin'])) {
            PetugasUks::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->name,
                'nip' => $request->nip ?? 'NIP-' . $user->id,
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
