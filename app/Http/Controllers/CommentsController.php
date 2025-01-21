<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function addcomment(Request $request, $id){

        $addedcomment = $request->all();

        $addedcomment['post_id'] = $id;
        $addedcomment['user_id'] = Auth::user()->id;

        Comments::create($addedcomment);


        
        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    public function postcomments(Request $request, $id){
        $post = Posts::findOrFail($id);

        $comments = Comments::with('user')->where('post_id', $id)->get();

        return view('instafeed.commentsection', compact('post', 'comments'));
    }


    public function updatecomment(Request $request, $id){
        $comment = Comments::findOrFail($id);

        $updatedcomment = $request->content;

        $comment->update(
            [
                'content'=>$updatedcomment,
            ]
            );
            return redirect()->back()->with('success', 'Comment updated successfully!');
    }

    public function deletecomment(Request $request, $id){
        $comment = Comments::findOrFail($id);

        $comment->delete();

        return redirect()->back()->with('success', 'Comment Deleted Successfully');
    }
        
    
}
