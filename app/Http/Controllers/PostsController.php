<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Follows;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts=Posts::where("user_id",Auth::user()->id)->orderBy("created_at","desc")->get();
        $postcount = Posts::where("user_id", Auth::user()->id)->count();
        $followcount = Follows::where("followed_id", Auth::user()->id)->count();
        $isfollowingcount = Follows::where("follower_id", Auth::user()->id)->count();
        return view("home" , compact("posts", "postcount", "followcount", "isfollowingcount"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("posts.add");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' =>['required'],
            'caption'=>[ 'max:255 '],
        ]);
        
        
        
        
        $mypost = $request->except('image');
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imgname = uniqid(). "." .$image->getClientOriginalExtension();
            $image = $image->move('images/', $imgname);
            $imagepath = "images/$imgname";


            $mypost['image'] = $imagepath;
        }

        $mypost['user_id'] = Auth::user()->id;

        Posts::create($mypost);
        return redirect()->route('home')->with('success','');

    }

    public function show($id)
{
    $posts = Posts::with('user')->findOrFail($id);
    $comments = Comments::with('user')
                         ->where('post_id', $id)  
                         ->get(); 

    return view('posts.view', compact('posts', 'comments')); 
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $newcaption = $request->caption;
        
        $post=Posts::find($id);

        $post->update([
            'caption' => $newcaption
        ]);

        return redirect()->route('posts.show',$id)->with('success','');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $post = Posts::findOrFail($id);
        
        $post->delete();

        return redirect()->route('home')->with('success','');
    }

public function showuser($id)
{
    $user = User::findOrFail($id);
    $posts = Posts::where("user_id", $id)->orderBy("created_at", "desc")->get();
    $postcount = Posts::where("user_id", $id)->count();
    $followcount = Follows::where("followed_id", $id)->count();
    $isfollowingcount = Follows::where("follower_id", $id)->count();

    return view("instafeed.userprofile", compact("user", "posts", "postcount","followcount", "isfollowingcount"));
}
}
