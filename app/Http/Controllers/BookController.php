<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|url',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'category' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:13',
            'page_count' => 'nullable|integer|min:1',
        ]);

        $book = Book::create($validatedData);

        return response()->json(['message' => 'Book added successfully', 'book' => $book], 201);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }
}
