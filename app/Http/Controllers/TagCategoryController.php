<?php

namespace App\Http\Controllers;

use App\TagCategory;
use App\OfferTag;
use App\Http\Resources\Tags\TagCategory as TagCategoryResources;
use App\Jobs\ProcessTagCategory;
use Illuminate\Http\Request;

class TagCategoryController extends Controller
{
    public function index()
    {
        return TagCategoryResources::collection(TagCategory::all());
    }

    public static function storeCategory($request)
    {
        $category = new TagCategory;
        $category->label = $request['label'];
        $category->save();
    }

    public function dispatchStoreCategory(Request $request)
    {
        ProcessTagCategory::dispatch($request->all(), 'store')->onQueue('default');
        return response()->json(['status' => 'success'], 200);
    }

    public function deleteCategory(Request $request) {
        $categoryToDelete = TagCategory::find($request->category_id);
        $dependantOffersTags = OfferTag::where('category_id', '=', $categoryToDelete->id)->get();
        if (!$dependantOffersTags->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Some offers already depend on this category!'
            ], 200);
        }
        $categoryToDelete->delete();
    }
}
