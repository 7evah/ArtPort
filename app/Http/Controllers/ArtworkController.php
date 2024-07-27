<?php
// app/Http/Controllers/ArtworkController.php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArtworkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $artworks = Artwork::where('user_id', $user->id)->get();
        return response()->json($artworks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('artworks', 'public');

        $artwork = new Artwork();
        $artwork->title = $request->title;
        $artwork->description = $request->description;
        $artwork->image_path = $path;
        $artwork->user_id = Auth::id();
        $artwork->save();

        return response()->json($artwork, 201);
    }

    public function show(Artwork $artwork)
    {
        return response()->json($artwork);
    }

    public function update(Request $request, Artwork $artwork)
    {
        if (Auth::id() !== $artwork->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
        ]);
    
        $artwork->title = $request->title;
        $artwork->description = $request->description;
    
        if ($request->hasFile('image')) {
            // Delete the old image from storage
            Storage::disk('public')->delete($artwork->image_path);
    
            // Store the new image
            $path = $request->file('image')->store('artworks', 'public');
            $artwork->image_path = $path;
        }
    
        $artwork->save();
    
        return response()->json($artwork);
    }
    

    public function destroy(Artwork $artwork)
    {
        if (Auth::id() !== $artwork->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the image from storage
        Storage::disk('public')->delete($artwork->image_path);

        $artwork->delete();

        return response()->json(null, 204);
    }
}

