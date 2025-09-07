<?php

namespace App\Http\Controllers\Posts;

use App\Models\Posts\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\Posts\PostResource;
use App\Http\Requests\Posts\StorePostFormRequest;
use App\Http\Resources\Posts\PostSummaryResource;
use App\Http\Requests\Posts\UpdatePostFormRequest;

class PostController extends Controller
{
    public function index(Request $request){
        Gate::authorize('viewAny', Post::class);
        
        $user    = $request->user();
        $term    = trim((string) $request->input('search', ''));
        $summary = $request->boolean('summary');
        
        $query = Post::query();
        
        if ($user?->can('posts.view.any') || $user?->can('posts.manage.any')) {
        } elseif ($user?->can('posts.view.self')) {
            $query->where(function ($q) use ($user) {
                $q->where('visibility', Post::VISIBILITY_PUBLIC)
                ->orWhere('author_id', $user->getKey());
            });
        } else {
            $query->where('visibility', Post::VISIBILITY_PUBLIC);
        }
        
        if ($term !== '') {
            $like = "%{$term}%";
            $query->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                ->orWhere('subtitle', 'like', $like)
                ->orWhereHas('author', fn ($qa) => $qa->where('name', 'like', $like))
                ->orWhereHas('category', fn ($qc) => $qc->where('name','like',$like));
            });
        }
        
        if ($summary) {
            $query->select([
                'id','title','subtitle','image_url','author_id','post_category_id','visibility','created_at'
                ])->with([
                    'author:id,name,photo_url',
                    'category:id,name,slug',
                ]);
            } else {
                $query->with([
                    'author:id,name,photo_url',
                    'category:id,name,slug',
                    'author.psychologistProfile:user_id,id,speciality',
                ]);
            }
            
            $posts = $query->latest()->paginate(15);
            return $summary
            ? PostSummaryResource::collection($posts)
            :  PostResource::collection($posts);
        }
        
        public function show(Request $request,Post $post){
            Gate::authorize("view",$post);
            return new PostResource($post);
        }
        
        public function store(StorePostFormRequest $request){
            $user = Auth::user();
            Gate::authorize('create',Post::class);
            $input              = $request->validated();
            $input['image_url'] = 'seila';
            $post               = $user->posts()->create($input);
            return new PostResource($post);
        }
        
        public function update(UpdatePostFormRequest $request, Post $post){
            Gate::authorize("update",$post);
            $input = $request->validated();
            $post->update($input);
            return new PostResource($post);
        }
        
        public function destroy(Request $request,Post $post){
            Gate::authorize("delete",$post);
            $post->delete();
            return response()->noContent();
        }
    }
