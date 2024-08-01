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



    public function update(Request $request, $artwork)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $artwork = Artwork::findOrFail($artwork);
        $artwork->title = $request->title;
        $artwork->description = $request->description;
    
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $artwork->image = $imageName;
        }
    
        $artwork->save();
    
        return response()->json(['message' => 'Artwork updated successfully']);
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

