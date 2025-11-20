<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $borrowings = Borrowing::with('book','user')->get();

        return response()->json($borrowings);
    }

    public function myBorrows()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $borrowings = Borrowing::with('book')
            ->where('user_id', $user->id)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereNull('return_date')
            ->get();

        return response()->json($borrowings);
    }

    public function store(Request $request)
    {
        // validasi input
        $request->validate([
            'book_id'=>'required|exists:books,id'
        ]);

        // tindakan setelah validasi
        $book = Book::findOrFail($request->book_id);
        if ($book->stock <= 0) {
            return response()->json(['message'=>'Book out of stock'],400);
        }

        $borrowing = Borrowing::create([
            'user_id'=>Auth::guard('api')->user()->id,
            'book_id'=>$book->id,
            'borrow_date'=>Carbon::now(),
            'due_date'=>Carbon::now()->addDays(7),
            'status'=>'dipinjam'
        ]);

        $book->decrement('stock');

        History::create([
            'borrowing_id' => $borrowing->id,
            'action' => 'borrow',
            'action_date' => now()
        ]);

        return response()->json([
            'message'=>'Book borrowed successfully',
            'data'=>$borrowing
        ],201);
    }

    public function update(Request $request, $id)
    {

        $this->authorizeAdmin();

        // validasi input
        $request->validate([
            'status'=>'sometimes|in:dipinjam,dikembalikan,terlambat',
            'due_date'=>'sometimes|date',
            'return_date'=>'sometimes|date'
        ]);

        $borrowing = Borrowing::findOrFail($id);
        $borrowing->update($request->all());

        return response()->json([
            'message'=>'Borrowing updated successfully',
            'data'=>$borrowing
        ]);
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();


        $borrowing = Borrowing::findOrFail($id);
        $borrowing->delete();

        return response()->json(['message'=>'Borrowing deleted successfully']);
    }

    public function returnBook(Request $request)
    {
        $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id'
        ]);

        $borrowing = Borrowing::find($request->borrowing_id);

        if (!$borrowing) {
            return response()->json(['message' => 'Borrowing not found'], 404);
        }

        if ($borrowing->status === 'dikembalikan') {
            return response()->json(['message' => 'The book has already been returned previously'], 400);
        }

        $borrowing->update([
            'status' => 'dikembalikan',
            'return_date' => now()->toDateString(),
        ]);


        History::create([
            'borrowing_id' => $borrowing->id,
            'action' => 'return',
            'action_date' => now()
        ]);

        return response()->json([
            'message' => 'Book returned successfully',
            'data' => $borrowing
        ]);
    }

    public function userBorrows($id)
    {
        $this->authorizeAdmin();

        $userBorrows = Borrowing::with('book')->where('user_id',$id)->get();

        return response()->json($userBorrows);
    }

    private function authorizeAdmin()
    {
        if (auth('api')->user()->role !== 'admin') {
            abort(403,'Admin only');
        }
    }


}
