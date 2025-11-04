<?php

namespace App\Modules\Posts\Http\Controllers;

use App\Modules\Posts\Http\Requests\StorePostCategoryFormRequest;
use App\Modules\Posts\Http\Resources\PostCategoryResource;
use App\Http\Controllers\Controller;
use App\Modules\Posts\Http\Requests\UpdatePostCategoryFormRequest;
use App\Modules\Posts\Models\PostCategory;
use App\Modules\Posts\Actions\PostCategory\IndexPostCategoryAction;
use App\Modules\Posts\Actions\PostCategory\ShowPostCategoryAction;
use App\Modules\Posts\Actions\PostCategory\StorePostCategoryAction;
use App\Modules\Posts\Actions\PostCategory\UpdatePostCategoryAction;
use App\Modules\Posts\Actions\PostCategory\DestroyPostCategoryAction;

class PostCategoryController extends Controller
{
    public function index(IndexPostCategoryAction $action)
    {
        $categories = $action->execute();
        return PostCategoryResource::collection($categories);
    }

    public function store(StorePostCategoryFormRequest $request, StorePostCategoryAction $action)
    {
        $input = $request->validated();
        $category = $action->execute($input);
        return new PostCategoryResource($category);
    }

    public function show(PostCategory $category, ShowPostCategoryAction $action)
    {
        $category = $action->execute($category);
        return new PostCategoryResource($category);
    }

    public function update(UpdatePostCategoryFormRequest $request, PostCategory $category, UpdatePostCategoryAction $action)
    {
        $input = $request->validated();
        $category = $action->execute($input, $category);
        return new PostCategoryResource($category);
    }

    public function destroy(PostCategory $category, DestroyPostCategoryAction $action)
    {
        $action->execute($category);
        return response()->noContent();
    }
}
