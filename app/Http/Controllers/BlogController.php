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
        
        $recentQuery = Post::where('id', '!=', $post->id);

        if (!auth()->check() || auth()->user()->role !== 'admin') {
            $recentQuery->published();
        }

        $recentPosts = $recentQuery->latest('published_at')
            ->take(3)
            ->get();

        // SEO Data
        $title = $post->title . ' | FlyoverBD Blog';
        $meta_description = $post->seo_description ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 155);
        $meta_image = $post->image 
            ? (\Illuminate\Support\Str::startsWith($post->image, 'http') ? $post->image : \Illuminate\Support\Facades\Storage::url($post->image))
            : asset('logo.png');

        return view('blog.show', compact('post', 'recentPosts', 'title', 'meta_description', 'meta_image'));
    }
}
