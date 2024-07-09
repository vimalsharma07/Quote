<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Comment;


class CommentController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'quote_id' => 'required|exists:quotes,id',
        'comment' => 'required|string|max:255',
    ]);

    $quote = Quote::findOrFail($request->quote_id);
    $comment = new Comment([
        'user_id' => auth()->id(),
        'comment' => $request->comment,
    ]);

    $quote->comments()->save($comment);

    return back();
}

}
