<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // Define fillable properties to allow mass assignment
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
}
