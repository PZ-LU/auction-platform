<?php

namespace App\Http\Controllers;

use App\TagCategory;
use App\OfferTag;
use App\Http\Resources\Tags\TagCategory as TagCategoryResources;
use Illuminate\Http\Request;

class TagCategoryController extends Controller
{
    public function index()
    {
        return TagCategoryResources::collection(TagCategory::all());
    }

    public function storeCategory(Request $request)
    {
        $category = new TagCategory;
        $category->label = $request->label;
        $category->save();
        return response()->json(['status' => 'success'], 200);
    }

    public function deleteCategory(Request $request) {
        $categoryToDelete = TagCategory::find($request->category);
        $dependantOffersTags = OfferTag::where('category', '=', $categoryToDelete->id)->get();
        if (!$dependantOffersTags->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Some offers already depend on this category!'
            ], 200);
        }
        $categoryToDelete->delete();
        return;
    }
}
