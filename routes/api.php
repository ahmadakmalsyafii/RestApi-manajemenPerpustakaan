<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\OverdueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
return response()->json(['message' => 'pong']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    // Fungsional utama (logout, profil)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // Peminjaman & Pengembalian Buku
    Route::post('/borrow', [BorrowController::class, 'store']);
    Route::post('/return', [BorrowController::class, 'returnBook']);

    // Riwayat Peminjaman (milik sendiri)
    Route::get('/history', [HistoryController::class, 'index']);

    // Notifikasi Keterlambatan (milik sendiri)
    Route::get('/overdue/me', [OverdueController::class, 'myOverdue']);
});

Route::middleware(['auth:api', 'can:is-admin'])->group(function () {
    // CRUD Buku (Admin)
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);

    // CRUD User (Data Anggota Perpustakaan)
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Manajemen Peminjaman (Admin)
    Route::get('/borrow', [BorrowController::class, 'index']);
    Route::put('/borrow/{id}', [BorrowController::class, 'update']);
    Route::delete('/borrow/{id}', [BorrowController::class, 'destroy']);
    Route::get('/borrow/users/{id}', [BorrowController::class, 'userBorrows']);

    // Manajemen Riwayat Peminjaman (Admin)
    Route::get('/history/{user_id}', [HistoryController::class, 'show']);
    Route::post('/history', [HistoryController::class, 'store']);
    Route::put('/history/{user_id}', [HistoryController::class, 'update']);
    Route::delete('/history/{id}', [HistoryController::class, 'destroy']);

    // Manajemen Keterlambatan (Admin)
    Route::get('/overdue', [OverdueController::class, 'index']);
    Route::get('/overdue/{user_id}', [OverdueController::class, 'show']);
    // Catatan: DELETE dan PUT untuk /overdue tidak ada di kode Anda, jadi saya tidak menambahkannya.
});




// Route::middleware('auth:api')->group(function () {
//     Route::post("/logout",[AuthController::class,'logout']);
// });

//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/profile', [ProfileController::class, 'show']);
//     Route::put('/profile', [ProfileController::class, 'update']);
//     Route::delete('/profile', [ProfileController::class, 'destroy']);

//     // Books
//     Route::get('/books', [BookController::class, 'index']);
//     Route::get('/books/{id}', [BookController::class, 'show']);
//     Route::post('/books', [BookController::class, 'store']);
//     Route::put('/books/{id}', [BookController::class, 'update']);
//     Route::patch('/books/{id}', [BookController::class, 'update']);
//     Route::delete('/books/{id}', [BookController::class, 'destroy']);

//     // Users (admin)
//     // Route::middleware('can:isAdmin')->group(function () {
//     //     Route::get('/users', [UserController::class, 'index']);
//     //     Route::get('/users/{id}', [UserController::class, 'show']);
//     //     Route::post('/users', [UserController::class, 'store']);
//     //     Route::put('/users/{id}', [UserController::class, 'update']);
//     //     Route::delete('/users/{id}', [UserController::class, 'destroy']);
//     // });

//     Route::get('/users', [UserController::class, 'index']);
//     Route::get('/users/{id}', [UserController::class, 'show']);
//     Route::post('/users', [UserController::class, 'store']);
//     Route::put('/users/{id}', [UserController::class, 'update']);
//     Route::delete('/users/{id}', [UserController::class, 'destroy']);

//     // Borrow
//     Route::get('/borrow', [BorrowController::class, 'index']);
//     Route::post('/borrow', [BorrowController::class, 'store']);
//     Route::put('/borrow/{id}', [BorrowController::class, 'update']);
//     Route::patch('/borrow/{id}', [BorrowController::class, 'update']);
//     Route::delete('/borrow/{id}', [BorrowController::class, 'destroy']);
//     Route::post('/return', [BorrowController::class, 'returnBook']);
//     Route::get('/borrow/users/{id}', [BorrowController::class, 'userBorrows']);


//     Route::get('/history', [HistoryController::class, 'index']);
//     Route::get('/history/{user_id}', [HistoryController::class, 'show']);
//     Route::post('/history', [HistoryController::class, 'store']);
//     Route::put('/history/{user_id}', [HistoryController::class, 'update']);
//     Route::delete('/history/{id}', [HistoryController::class, 'destroy']);


//     // Route::get('/history', [HistoryController::class, 'index']); // riwayat user login
//     // Route::get('/history/{user_id}', [HistoryController::class, 'show']);


//     // Overdue
//     Route::get('/overdue', [OverdueController::class, 'index']);
//     Route::get('/overdue/me', [OverdueController::class, 'myOverdue']);
//     Route::get('/overdue/{user_id}', [OverdueController::class, 'show']);



//     Route::post('register', [AuthController::class, 'register']);
// Route::post('login', [AuthController::class, 'login']);

// Route::middleware('auth:api')->group(function () {
//     Route::post('logout', [AuthController::class, 'logout']);
//     Route::get('profile', [AuthController::class, 'profile']);
//     Route::put('profile', [AuthController::class, 'updateProfile']);
//     Route::delete('profile', [AuthController::class, 'deleteProfile']);

//     // Peminjaman & Pengembalian Buku
//     Route::post('borrow', [BorrowController::class, 'borrowBook']);
//     Route::post('return', [BorrowController::class, 'returnBook']);

//     // Riwayat & Keterlambatan User
//     Route::get('history', [BorrowController::class, 'myHistory']);
//     Route::get('overdue/me', [BorrowController::class, 'myOverdue']);

//     // Admin Only Routes
//     Route::middleware('admin')->group(function () {
//         // CRUD Buku (Admin)
//         Route::apiResource('books', BookController::class);

//         // CRUD User (Data Anggota Perpustakaan)
//         Route::apiResource('users', UserController::class);

//         // Peminjaman (Admin View)
//         Route::get('borrow', [BorrowController::class, 'allBorrows']);
//         Route::get('borrow/users/{id}', [BorrowController::class, 'userBorrows']);

//         // Riwayat & Keterlambatan (Admin View)
//         Route::get('history/{user_id}', [BorrowController::class, 'userHistory']);
//         Route::get('overdue', [BorrowController::class, 'allOverdue']);
//         Route::get('overdue/{user_id}', [BorrowController::class, 'userOverdue']);
//     });
// });

// // Pencarian & Filter Buku (Publik)
// Route::get('books', [BookController::class, 'index']);
// Route::get('books/{id}', [BookController::class, 'show']);

