<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()->latest('published_at')->paginate(9);
        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $query = Post::where('slug', $slug);

        if (!auth()->check() || auth()->user()->role !== 'admin') {
            $query->published();
        }

        $post = $query->firstOrFail();
        return view('blog.show', compact('post'));
    }
}
