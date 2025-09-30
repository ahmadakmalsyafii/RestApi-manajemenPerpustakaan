<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{

     // GET /books
    public function index(Request $request)
    {
        // validasi input (opsional, filter pencarian)
        $request->validate([
            'title' => 'nullable|string',
            'author' => 'nullable|string',
            'category' => 'nullable|string',
        ]);

        // tindakan setelah validasi
        $query = Book::query();
        if ($request->has('title')) {
            $query->where('title', 'like', "%{$request->title}%");
        }
        if ($request->has('author')) {
            $query->where('author', 'like', "%{$request->author}%");
        }
        if ($request->has('category')) {
            $query->where('category', 'like', "%{$request->category}%");
        }

        // mengembalikan response json
        return response()->json($query);
    }

    // GET /books/{id}
    public function show($id)
    {
        // validasi input
        $book = Book::findOrFail($id);

        // mengembalikan response json
        return response()->json($book);
    }

    // POST /books (admin only)
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        // validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0'
        ]);

        // tindakan setelah validasi
        $book = Book::create($request->all());

        // mengembalikan response json
        return response()->json([
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }

    // PUT /books/{id}
    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        // validasi input
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:100',
            'stock' => 'sometimes|integer|min:0'
        ]);

        // tindakan setelah validasi
        $book = Book::findOrFail($id);
        $book->update($request->all());

        // mengembalikan response json
        return response()->json([
            'message' => 'Book updated successfully',
            'data' => $book
        ]);
    }

    // DELETE /books/{id}
    public function destroy($id)
    {
        $this->authorizeAdmin();

        // validasi input
        $book = Book::findOrFail($id);

        // tindakan setelah validasi
        $book->delete();

        // mengembalikan response json
        return response()->json(['message' => 'Book deleted successfully']);
    }

    private function authorizeAdmin()
    {
        if (auth('api')->user()->role !== 'admin') {
            abort(403, 'Admin only');
        }
    }


}
