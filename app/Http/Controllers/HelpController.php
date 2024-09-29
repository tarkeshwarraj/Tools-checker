<?php
namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function store(Request $request){
        //Validate the request
        $request->validate([
            'comment' => 'required| max:500',
            'user_id' => 'required',
        ]);

        // Save the comment in the database
        Comment::create([
            'user_id' => $request->user_id,
            'comment' => $request->comment,
        ]);

        return response()-> json(['success' => true]);
    }

    public function getComments()
{
    $comments = Comment::all(); // Retrieve all comments from the database
    return response()->json(['comments' => $comments]);
}
}
