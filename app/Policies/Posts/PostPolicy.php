<?php

namespace App\Policies\Posts;

use App\Models\Posts\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
    * Determine whether the user can view any models.
    */
    public function viewAny(User $user): bool
    {

        if (!$user) {
            return true;
        }

        if ($user->can('posts.view.any')) {
            return true;
        }

        if ($user->can('posts.view.self')) {
            return true;
        }

        return false;
    }
    
    /**
    * Determine whether the user can view the model.
    */
    public function view(User $user, Post $post): bool
    {
        if ($post->visibility === Post::VISIBILITY_PUBLIC) {
            return true;
        }
        
        if (!$user) {
            return false;
        }
        
        if ($user->can('posts.view.any')) {
            return true;
        }
        
        if (
            $post->visibility === Post::VISIBILITY_PRIVATE &&
            $user->can('posts.view.self') &&
            (string) $post->author_id === (string) $user->getKey()
            ) {
                return true;
            }
            
        return false;
    }
    
        /**
        * Determine whether the user can create models.
        */
        public function create(User $user): bool
        {
            if($user->can('posts.create.self') || $user->can('posts.manage.any')) {
                return true;
            }
            
            return false;
        }
        
        /**
        * Determine whether the user can update the model.
        */
        public function update(User $user, Post $post): bool
        {
            if($user->can('posts.update.self') && $post->author_id === $user->id) {
                return true;
            }
            if($user->can('posts.manage.any')) {
                return true;
            }
            return false;
        }
        
        /**
        * Determine whether the user can delete the model.
        */
        public function delete(User $user, Post $post): bool
        {
            if($user->can('posts.delete.self') && $post->author_id === $user->id) {
                return true;
            }
            if($user->can('posts.manage.any')) {
                return true;
            }
            return false;
        }
        
        /**
        * Determine whether the user can restore the model.
        */
        public function restore(User $user, Post $post): bool
        {
            return false;
        }
        
        /**
        * Determine whether the user can permanently delete the model.
        */
        public function forceDelete(User $user, Post $post): bool
        {
            return false;
        }
    }
