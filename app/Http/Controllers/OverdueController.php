<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class OverdueController extends Controller
{

    // 🔹 Semua overdue (admin only)
    public function index()
    {
        $this->authorizeAdmin();

        $overdue = Borrowing::with(['book', 'user'])
            ->where('due_date', '<', now())
            ->whereNull('return_date')
            ->paginate(10);

        return response()->json([
            'message' => 'Daftar semua keterlambatan pengembalian',
            'data' => $overdue
        ]);
    }

    // 🔹 Overdue milik user login
    public function myOverdue()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $overdue = Borrowing::with('book')
            ->where('user_id', $user->id)
            ->where('due_date', '<', now())
            ->whereNull('return_date')
            ->get();

        return response()->json([
            'message' => 'Daftar keterlambatan buku saya',
            'data' => $overdue
        ]);
    }

    // 🔹 Overdue user tertentu (admin only)
    public function show($user_id)
    {
        $this->authorizeAdmin();

        $overdue = Borrowing::with('book')
            ->where('user_id', $user_id)
            ->where('due_date', '<', now())
            ->whereNull('return_date')
            ->get();

        return response()->json([
            'message' => 'Daftar keterlambatan user',
            'data' => $overdue
        ]);
    }

    // 🔹 Middleware admin check
    private function authorizeAdmin()
    {
        $user = auth('api')->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized: Admin only');
        }
    }

}
