<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'nullable|string',
        ]);

        $review = Review::create([
            'book_id' => $validatedData['book_id'],
            'user_id' => Auth::id(),
            'rating' => $validatedData['rating'],
            'message' => $validatedData['message'] ?? '',
        ]);

        return response()->json(['message' => 'Review created successfully', 'review' => $review], 201);
    }

    public function update(Request $request, Review $review)
    {

        $validatedData = $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'message' => 'nullable|string',
        ]);

        $review->update($validatedData);

        return response()->json(['message' => 'Review updated successfully', 'review' => $review]);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }
}
