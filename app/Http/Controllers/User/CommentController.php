<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Auth;

class CommentController extends Controller
{
    //
    public function store(Request $request){
        $comment = new Comment;
        $comment->quote_id= $request->quote_id;
        $comment->comment= $request->comment;
        $comment->user_id = Auth::user()->id;
        $comment->save();

        return redirect()->back();
    }
}
