<?php

namespace App\Http\Controllers;

use App\Models\Follows;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowsController extends Controller
{
    public function follow(Request $request, $id)
{
    $followerId = Auth::user()->id;

    if ($followerId === (int)$id) {
        return response()->json([
            'success' => false,
            'message' => 'You cannot follow yourself.'
        ], 400);
    }

    $follow = Follows::where('follower_id', $followerId)
        ->where('followed_id', $id)
        ->first();

    if ($follow) {
        $follow->delete();

        return response()->json([
            'success' => true,
            'action' => 'unfollowed',
            'message' => 'You have unfollowed the user.'
        ]);
    }

    Follows::create([
        'follower_id' => $followerId,
        'followed_id' => $id,
    ]);

    return response()->json([
        'success' => true,
        'action' => 'followed',
        'message' => 'You have followed the user.'
    ]);
}


public function isFollowing($id)
{
    $isFollowing = Follows::where('follower_id', Auth::user()->id)
        ->where('followed_id', $id)
        ->exists();

    return response()->json([
        'isFollowing' => $isFollowing,
    ]);
}

}
