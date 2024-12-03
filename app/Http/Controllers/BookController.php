<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function update(Request $request, Book $book)
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

        // Update the book
        $book->update($validatedData);

        return response()->json(['message' => 'Book updated successfully', 'book' => $book]);
    }

    public function index()
    {
        $userId = Auth::id();

        $books = Book::query()
            ->select('books.*')
            ->selectRaw('NOT EXISTS (SELECT 1 FROM borrows WHERE borrows.book_id = books.id AND borrows.is_returned = 0) as is_available')
            ->withCount([
                'reviews as average_rating' => function ($query) {
                    $query->select(DB::raw('avg(rating)'));
                },
                'reviews as review_count' => function ($query) {
                    $query->select(DB::raw('count(rating)'));
                },
            ])
            ->with([
                'reviews' => function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                },
            ])
            ->get();

        return response()->json($books);
    }
}
