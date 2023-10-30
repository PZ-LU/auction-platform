<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Traits\ForumTraits;
use App\Http\Resources\Topics\Topic as TopicResources;
use Illuminate\Http\Request;
use App\Jobs\ProcessTopic;

class TopicController extends Controller
{
    use ForumTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->category_id) {
            $topics = Topic::where('category_id', $request->category_id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        } else {
            $topics = Topic::all()->orderBy('created_at', 'desc');
        }

        foreach ($topics as $topic) {
            $topic->author_data = $this->getAuthor($topic->user_id);
        }

        return TopicResources::collection($topics);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($request)
    {
        $topic = new Topic;
        $topic->user_id = $request['user_id'];
        $topic->category_id = $request['category_id'];
        $topic->title = $request['title'];
        $topic->body = $request['body'];
        $topic->save();
    }

    public static function delete($request) {
        $topicToDelete = Topic::find($request['topic']);
        if ($topicToDelete)
            $topicToDelete->delete();
    }

    public function dispatchStore(Request $request)
    {
        ProcessTopic::dispatch($request->all(), 'store')->onQueue('default');
        return response()->json(['status' => 'success'], 200);
    }

    public function dispatchDelete(Request $request)
    {
        ProcessTopic::dispatch($request->all(), 'delete')->onQueue('default');
        return response()->json(['status' => 'success'], 200);
    }
}
