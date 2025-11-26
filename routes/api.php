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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    Route::post('/borrow', [BorrowController::class, 'store']);
    Route::post('/return', [BorrowController::class, 'returnBook']);
    Route::get('/borrow/me', [BorrowController::class, 'myBorrows']);

    Route::get('/history', [HistoryController::class, 'index']);

    Route::get('/overdue/me', [OverdueController::class, 'myOverdue']);
});

Route::middleware(['auth:api', 'can:is-admin'])->group(function () {
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/borrow', [BorrowController::class, 'index']);
    Route::put('/borrow/{id}', [BorrowController::class, 'update']);
    Route::delete('/borrow/{id}', [BorrowController::class, 'destroy']);
    Route::get('/borrow/users/{id}', [BorrowController::class, 'userBorrows']);

    Route::get('/history/{user_id}', [HistoryController::class, 'show']);
    Route::post('/history', [HistoryController::class, 'store']);
    Route::put('/history/{user_id}', [HistoryController::class, 'update']);
    Route::delete('/history/{id}', [HistoryController::class, 'destroy']);

    Route::get('/overdue', [OverdueController::class, 'index']);
    Route::get('/overdue/{user_id}', [OverdueController::class, 'show']);
});



