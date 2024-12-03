<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'cover_image',
        'publisher',
        'publication_date',
        'category',
        'isbn',
        'page_count',
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
