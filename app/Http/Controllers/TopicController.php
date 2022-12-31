<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Traits\ForumTraits;
use App\Http\Resources\Topics\Topic as TopicResources;
use Illuminate\Http\Request;

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
        if ($request->category) {
            $topics = Topic::where('category_id', $request->category)
                        ->orderBy('created_at', 'desc')
                        ->get();
        } else {
            $topics = Topic::all()->orderBy('created_at', 'desc');
        }

        foreach ($topics as $topic) {
            $topic->author_data = $this->getAuthor($topic->author_id);
        }

        return TopicResources::collection($topics);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $topic = new Topic;
        $topic->author_id = $request->user_id;
        $topic->category_id = $request->category_id;
        $topic->title = $request->title;
        $topic->body = $request->body;
        $topic->save();
        return;
    }

    public function delete(Request $request) {
        $topicToDelete = Topic::find($request->topic);
        $topicToDelete->delete();
        return;
    }
}
