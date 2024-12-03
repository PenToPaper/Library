<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Check if the book is already borrowed and not returned
        $alreadyBorrowed = Borrow::where('book_id', $validatedData['book_id'])
            ->where('is_returned', false)
            ->exists();

        if ($alreadyBorrowed) {
            return response()->json(['error' => 'Book is already borrowed.'], 422);
        }

        $borrow = Borrow::create([
            'book_id' => $validatedData['book_id'],
            'user_id' => auth()->id(),
            'borrow_date' => now(),
            'due_date' => now()->addDays(5),
            'is_returned' => false,
        ]);

        return response()->json(['message' => 'Book checked out successfully.', 'borrow' => $borrow]);
    }

    public function markReturned(Request $request)
    {
        $validatedData = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Use the query builder to find the record
        $borrow = Borrow::query()
            ->where('book_id', $validatedData['book_id'])
            ->where('is_returned', false)
            ->first();

        if (!$borrow) {
            return response()->json(['error' => 'This book is not currently borrowed.'], 404);
        }

        $borrow->update(['is_returned' => true]);

        return response()->json(['message' => 'Book marked as returned.']);
    }


}
