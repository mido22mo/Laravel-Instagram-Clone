<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function update(Request $request, $id)
{
    // Validate incoming data
    $request->validate([
        'name' => 'required|string|max:255',
        'bio' => 'nullable|string|max:500',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $user = User::findOrFail($id);

    $newProfileData = $request->except('image');
    
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imgName = uniqid() . "." . $image->getClientOriginalExtension();
        $image->move('images/', $imgName);
        $newProfileData['image'] = "images/{$imgName}";
    }

    $user->update($newProfileData);

    return redirect()->route('home')->with('success', 'Profile updated successfully');
}

public function search(Request $request)
{
    $query = $request->get('query');

    // Validate the query
    if (!$query) {
        return response()->json([]);
    }

    $users = User::where('name', 'like', '%' . $query . '%')->get();

    return response()->json($users);
}

public function feed(){

    $users = User::all();
    $posts = Posts::with('user', 'comments.user')->orderBy('created_at', 'desc')->get();

    return view('instafeed.feed', compact('users', 'posts'));

}


}
