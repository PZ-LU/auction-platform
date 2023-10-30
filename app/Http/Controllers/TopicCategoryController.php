<?php

namespace App\Http\Controllers;

use App\TopicCategory;
use App\Http\Resources\Topics\TopicCategory as TopicCategoryResources;
use App\Jobs\ProcessTopicCategory;
use Illuminate\Http\Request;

class TopicCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TopicCategoryResources::collection(TopicCategory::all());
    }

    public static function store($request)
    {
        $category = new TopicCategory;
        $category->label = $request['label'];
        $category->save();
    }

    public static function delete($request) {
        $categoryToDelete = TopicCategory::find($request['category_id']);
        if ($categoryToDelete)
            $categoryToDelete->delete();
    }

    public function dispatchStore(Request $request)
    {
        ProcessTopicCategory::dispatch($request->all(), 'store')->onQueue('default');
        return response()->json(['status' => 'success'], 200);
    }

    public function dispatchDelete(Request $request)
    {
        ProcessTopicCategory::dispatch($request->all(), 'delete')->onQueue('default');
        return response()->json(['status' => 'success'], 200);
    }
}
