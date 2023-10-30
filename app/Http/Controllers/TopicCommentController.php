<?php

namespace App\Http\Controllers;

use App\TopicComment;
use App\Traits\ForumTraits;
use App\Http\Resources\Topics\TopicComment as TopicCommentResources;
use App\Jobs\ProcessTopicComment;
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

    public static function store($request)
    {
        $comment = new TopicComment;
        $comment->user_id = $request['user_id'];
        $comment->topic_id = $request['topic_id'];
        $comment->body = $request['body'];
        $comment->save();
    }

    public static function delete($request) {
        $commentToDelete = TopicComment::find($request['comment']);
        if ($commentToDelete)
            $commentToDelete->delete();
    }

    public function dispatchStore(Request $request)
    {
        ProcessTopicComment::dispatch($request->all(), 'store')->onQueue('default');
        return response()->json(['status' => 'success'], 200);
    }

    public function dispatchDelete(Request $request)
    {
        ProcessTopicComment::dispatch($request->all(), 'delete')->onQueue('default');
        return response()->json(['status' => 'success'], 200);
    }
}
