<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    /** @use HasFactory<\Database\Factories\HistoryFactory> */
    use HasFactory;
    protected $fillable =
    [
        'borrowing_id',
        'action',
        'action_date'
    ];
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
