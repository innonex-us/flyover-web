<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('author')->latest()->paginate(10);
        return view('admin.blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'custom_author' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $slug = Str::slug($validated['title']);
        $count = Post::where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blog', 'public');
        }

        Post::create([
            'user_id' => auth()->id(),
            'custom_author' => $validated['custom_author'],
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'image' => $imagePath,
            'seo_title' => $validated['seo_title'],
            'seo_description' => $validated['seo_description'],
            'is_published' => $request->has('is_published'),
            'published_at' => $request->has('is_published') ? now() : null,
        ]);

        return redirect()->route('admin.blog.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $blog)
    {
        return view('admin.blog.edit', ['post' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'custom_author' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $slug = Str::slug($validated['title']);
        if ($slug !== $blog->slug) {
            $count = Post::where('slug', $slug)->where('id', '!=', $blog->id)->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
            $blog->slug = $slug;
        }

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $blog->image = $request->file('image')->store('blog', 'public');
        }

        $blog->title = $validated['title'];
        $blog->custom_author = $validated['custom_author'];
        $blog->content = $validated['content'];
        $blog->seo_title = $validated['seo_title'];
        $blog->seo_description = $validated['seo_description'];
        $blog->is_published = $request->has('is_published');
        
        if ($blog->is_published && !$blog->published_at) {
            $blog->published_at = now();
        }

        $blog->save();

        return redirect()->route('admin.blog.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $blog)
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Post deleted successfully.');
    }
}
