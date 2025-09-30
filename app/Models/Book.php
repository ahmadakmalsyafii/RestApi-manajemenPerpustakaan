<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;
    protected $fillable =
    [
        'title',
        'author',
        'category',
        'stock'
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
    public function histories()
    {
        return $this->hasMany(History::class);
    }

}
