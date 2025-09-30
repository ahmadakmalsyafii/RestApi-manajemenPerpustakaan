<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
     // GET /history → riwayat milik user login
    public function index()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $history = History::whereHas('borrowing', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('borrowing.book')->get();

        return response()->json($history);
    }

    // GET /history/{user_id} → riwayat user tertentu (admin only)
    public function show($user_id)
    {
        $this->authorizeAdmin();

        // Ambil history dengan relasi ke borrowing + book
        $history = History::with('borrowing.book')
            ->whereHas('borrowing', function($q) use ($user_id) {
                $q->where('user_id', $user_id);
            })->get();

        return response()->json([
            'message' => 'Riwayat peminjaman user',
            'data' => $history
        ]);

    }

    // POST /history → otomatis saat pinjam/kembali
    public function store(Request $request)
    {
        // validasi input
        $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'action' => 'required|in:borrow,return',
        ]);

        // tindakan setelah validasi
        $history = History::create([
            'borrowing_id' => $request->borrowing_id,
            'action' => $request->action,
            'action_date' => now(),
        ]);

        return response()->json([
            'message' => 'History record created successfully',
            'data' => $history
        ], 201);
    }

    // PUT /history/{user_id} → koreksi log (admin only)
    public function update(Request $request, $user_id)
    {
        $this->authorizeAdmin();

        // validasi input
        $request->validate([
            'action' => 'sometimes|in:borrow,return',
            'action_date' => 'sometimes|date'
        ]);

        // ambil history via user_id (riwayat user)
        $history = History::whereHas('borrowing', function($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->firstOrFail();

        // update data
        $history->update($request->all());

        return response()->json([
            'message' => 'History updated successfully',
            'data' => $history
        ]);
    }

    // DELETE /history/{id} → hapus log salah (admin only)
    public function destroy($id)
    {
        $this->authorizeAdmin();

        $history = History::findOrFail($id);
        $history->delete();

        return response()->json(['message' => 'History record deleted successfully']);
    }

    private function authorizeAdmin()
    {
        if (auth('api')->user()->role !== 'admin') {
            abort(403, 'Admin only');
        }
    }



}
