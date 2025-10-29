<?php

namespace App\Modules\Posts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Post;
use App\Modules\Posts\Http\Resources\PostResource;
use App\Modules\Posts\Http\Requests\StorePostFormRequest;
use App\Modules\Posts\Http\Resources\PostSummaryResource;
use App\Modules\Posts\Http\Requests\UpdatePostFormRequest;
use App\Modules\Posts\Actions\Post\IndexPostAction;
use App\Modules\Posts\Actions\Post\ShowPostAction;
use App\Modules\Posts\Actions\Post\StorePostAction;
use App\Modules\Posts\Actions\Post\UpdatePostAction;
use App\Modules\Posts\Actions\Post\DestroyPostAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index(Request $request, IndexPostAction $action)
    {
        Gate::authorize('viewAny', Post::class);

        $queryParams = $request->query();
        $summary = (bool) ($queryParams['summary'] ?? false);
        $posts = $action->execute($queryParams, $request->user());
        return $summary
            ? PostSummaryResource::collection($posts)
            : PostResource::collection($posts);
    }

    public function show(Request $request, Post $post, ShowPostAction $action)
    {
        Gate::authorize("view", $post);
        $post = $action->execute($post);
        return new PostResource($post);
    }

    public function store(StorePostFormRequest $request, StorePostAction $action)
    {
        $user = Auth::user();
        Gate::authorize('create', Post::class);
        $input = $request->validated();
        $post  = $action->execute($input, $user);
        return new PostResource($post);
    }

    public function update(UpdatePostFormRequest $request, Post $post, UpdatePostAction $action)
    {
        Gate::authorize("update", $post);
        $input = $request->validated();
        $post = $action->execute($input, $post);
        return new PostResource($post);
    }

    public function destroy(Request $request, Post $post, DestroyPostAction $action)
    {
        Gate::authorize("delete", $post);
        $action->execute($post);
        return response()->noContent();
    }
}
