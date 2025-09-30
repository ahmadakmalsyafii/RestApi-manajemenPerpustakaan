<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan data profil dari pengguna yang terotentikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        // Mengambil data user yang sedang login
        $user = $request->user();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil ditampilkan.',
            'data' => $user
        ]);
    }

    public function update(Request $request)
    {
        // Mengambil data user yang sedang login
        $user = $request->user();

        // Validasi input
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => [
                'nullable', // Password boleh kosong
                'string',
                Password::min(6),
                'confirmed',
            ],
        ]);

        // Update data jika ada di request
        if (isset($validatedData['name'])) {
            $user->name = $validatedData['name'];
        }

        if (isset($validatedData['email'])) {
            $user->email = $validatedData['email'];
        }

        // Update password jika diisi dan divalidasi
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        // Simpan perubahan
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $user
        ]);
    }

    public function destroy(Request $request)
    {
        // Mengambil user yang sedang login
        $user = $request->user();

        // Menghapus semua token API milik user (membuat user logout dari semua perangkat)
        Auth::guard('api')->logout();

        // Hapus akun user dari database
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akun Anda telah berhasil dihapus.'
        ]);
    }
}
