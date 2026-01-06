<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        
        // Check if user has already reviewed this product
        if (Auth::user()->hasReviewed($validated['product_id'])) {
            return back()->with('error', 'You have already reviewed this product.');
        }
        
        // Create the review
        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true, // Auto-approve for now
        ]);
        
        return back()->with('success', 'Your review has been submitted successfully!');
    }
    
    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Review $review)
    {
        // Check if the review belongs to the authenticated user
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to update this review.');
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        
        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);
        
        return back()->with('success', 'Your review has been updated successfully!');
    }
    
    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        // Check if the review belongs to the authenticated user
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return back()->with('error', 'You are not authorized to delete this review.');
        }
        
        $review->delete();
        
        return back()->with('success', 'Review has been deleted successfully!');
    }
}