<?php

namespace App\Http\Controllers;

use App\Models\Likes;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function toggleLike(Request $request, $id)
{
    $likerid = Auth::user()->id;
    $post = Posts::findOrFail($id);

    $existingLike = Likes::where('liker_id', $likerid)
                         ->where('liked_id', $id)
                         ->first();

    if ($existingLike) {
        $existingLike->delete();
        return response()->json(['message' => 'Like removed'], 200);
    } else {
        Likes::create([
            'liker_id' => $likerid,
            'liked_id' => $id,
        ]);
        return response()->json(['message' => 'Like added'], 200);
    }
}

public function likesection(Request $request, $id){
    $post = Posts::findOrFail($id);

    $likes = Likes::with('user')->where('liked_id', $id)->get();

    return view('instafeed.likesection', compact('post', 'likes'));
}

}
