<?php

namespace App\Http\Controllers;

use App\TagCategory;
use App\Http\Resources\Tags\TagCategory as TagCategoryResources;
use Illuminate\Http\Request;

class TagCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TagCategoryResources::collection(TagCategory::all());
    }
}
