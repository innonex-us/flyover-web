<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $query = Post::latest('published_at');

        if (!auth()->check() || auth()->user()->role !== 'admin') {
            $query->published();
        }

        $posts = $query->paginate(9);
        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $query = Post::where('slug', $slug);

        if (!auth()->check() || auth()->user()->role !== 'admin') {
            $query->published();
        }

        $post = $query->firstOrFail();
        
        $recentPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'recentPosts'));
    }
}
