<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    /** @use HasFactory<\Database\Factories\BorrowingFactory> */
    use HasFactory;
    protected $fillable =
    [
        'user_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status'
    ];
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function histories()
    {
        return $this->hasMany(History::class);
    }

}
