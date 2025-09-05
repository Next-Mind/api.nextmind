<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Posts\Post;

class PostController extends Controller
{
    public function index(Request $request){
        $term = trim((string) $request->input('search', ''));
        
        $query = Post::query()
        ->with(['author:id,name', 'category:id,name,slug']);
        
        if ($term !== '') {
            $like = "%{$term}%";
            
            $query->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                ->orWhere('subtitle', 'like', $like)
                ->orWhereHas('author', fn ($qa) => $qa->where('name', 'like', $like))
                ->orWhereHas('category', fn ($qc) => $qc->where('name','like',$like));
            });
        }
        
        return $query->latest()->paginate(15);
    }

    public function show(Request $request){}

    public function store(Request $request){}

    public function update(Request $request){}

    public function destroy(Request $request){}
}
