<?php

namespace App\Modules\Posts\Http\Controllers;

use App\Modules\Posts\Http\Requests\StorePostCategoryFormRequest;
use App\Modules\Posts\Http\Resources\PostCategoryResource;
use App\Http\Controllers\Controller;
use App\Modules\Posts\Http\Requests\UpdatePostCategoryFormRequest;
use App\Modules\Posts\Models\PostCategory;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index()
    {
        $categories = PostCategory::all();
        return PostCategoryResource::collection($categories);
    }

    public function store(StorePostCategoryFormRequest $request)
    {
        $input = $request->validated();
        $slug = Str::slug(title: $input["name"], language: 'pt');
        $category = PostCategory::create([
            'name' => $input['name'],
            'slug' => $slug
        ]);

        return new PostCategoryResource($category);
    }

    public function show(PostCategory $category)
    {
        return new PostCategoryResource($category);
    }

    public function update(UpdatePostCategoryFormRequest $request, PostCategory $category)
    {
        $input = $request->validated();
        $slug = Str::slug(title: $input["name"], language: 'pt');
        $category->update([
            'name' => $input['name'],
            'slug' => $slug
        ]);

        return new PostCategoryResource($category);
    }

    public function destroy(PostCategory $category)
    {
        $category->delete();
        return response()->noContent();
    }
}
