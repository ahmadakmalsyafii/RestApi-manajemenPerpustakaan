<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{

     // GET /books
    public function index(Request $request)
    {
        $query = Book::query();


        if ($request->has('title')) {
            $title = $request->input('title');
            $query->where('title', 'like', '%' . $title . '%');
        }
        if ($request->has('author')) {
            $author = $request->input('author');
            $query->where('author', 'like', "%{$author}%");
        }
        if ($request->has('category')) {
            $category = $request->input('category');
            $query->where('category', $category);
        }

        $books = $query->get();

        if ($books->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada buku yang ditemukan dengan kriteria pencarian tersebut.'
            ], 404);
        }

        return response()->json($books);
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
