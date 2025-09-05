<?php

namespace App\Http\Controllers\Posts;

use App\Http\Requests\Posts\StorePostCategoryFormRequest;
use App\Http\Resources\Posts\PostCategoryResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\UpdatePostCategoryFormRequest;
use App\Models\Posts\PostCategory;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index(){
        $categories = PostCategory::all();
        return PostCategoryResource::collection($categories);
    }

    public function store(StorePostCategoryFormRequest $request){
        $input = $request->validated();
        $slug = Str::slug(title: $input["name"],language: 'pt');
        $category = PostCategory::create([
            'name' => $input['name'],
            'slug' => $slug
        ]);

        return new PostCategoryResource($category);
    }

    public function show(PostCategory $category){
        return new PostCategoryResource($category);
    }

    public function update(UpdatePostCategoryFormRequest $request, PostCategory $category){
        $input = $request->validated();
        $slug = Str::slug(title: $input["name"],language: 'pt');
        $category->update([
            'name' => $input['name'],
            'slug' => $slug
        ]);

        return new PostCategoryResource($category);
    }

    public function destroy(PostCategory $category){
        $category->delete();
        return response()->noContent();
    }
}
