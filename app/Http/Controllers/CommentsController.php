<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Comments;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $comments = Comment::where('product_id', $id)->get();
            return response()->json([
                'data' => $comments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $comment = new Comment();
            $comment->Auther = $request->Auther;
            $comment->body = $request->body;
            $comment->user_id = $request->user_id;
            $comment->product_id = $request->product_id;
            $comment->save();
            return response()->json([
                'message' => 'comment added'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->body = $request->body;
            $comment->save();
            return response()->json([
                'message' => 'comment edited'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $order = Comment::findOrFail($id);
            $order->delete();
            return response()->json([
                'message' => 'Comment is deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }
}
