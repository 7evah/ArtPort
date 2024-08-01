<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VisitorController extends Controller
{
    public function index()
    {
        $artworks = Artwork::with('comments.user')->get();
        return response()->json($artworks);
    }

    public function show($id)
    {
        $artwork = Artwork::with('comments.user')->findOrFail($id);
        return response()->json($artwork);
    }

    public function comment(Request $request, $id)
{
    $request->validate([
        'comment' => 'required|string',
    ]);

    $comment = new Comment();
    $comment->artwork_id = $id;
    $comment->user_id = Auth::id();
    $comment->name = Auth::user()->name; // Save the user's name
    $comment->comment = $request->input('comment');
    $comment->save();

    return response()->json($comment, 201);
}

}
