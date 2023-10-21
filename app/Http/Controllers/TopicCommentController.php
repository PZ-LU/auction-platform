<?php

namespace App\Http\Controllers;

use App\TopicComment;
use App\Traits\ForumTraits;
use App\Http\Resources\Topics\TopicComment as TopicCommentResources;
use Illuminate\Http\Request;

class TopicCommentController extends Controller
{
    use ForumTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->topic) {
            $comments = TopicComment::where('topic_id', $request->topic)->orderByDesc('created_at')->get();
        } else {
            $comments = TopicComment::all()->sortByDesc('created_at');
        }

        foreach ($comments as $comment) {
            $comment->author_data = $this->getAuthor($comment->user_id);
        }

        return TopicCommentResources::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comment = new TopicComment;
        $comment->user_id = $request->user_id;
        $comment->topic_id = $request->topic_id;
        $comment->body = $request->body;
        $comment->save();
    }

    public function delete(Request $request) {
        $commentToDelete = TopicComment::find($request->comment);
        $commentToDelete->delete();
        return;
    }
}
