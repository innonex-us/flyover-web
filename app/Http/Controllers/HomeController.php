<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Package;
use App\Models\Visa;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        // Cache home page data for 10 minutes to reduce database load
        $packages = Cache::remember('home.packages', 600, function () {
            return Package::where('is_active', true)
                ->select('id', 'title', 'slug', 'thumbnail', 'images', 'price', 'duration_days', 'location', 'description')
                ->latest()
                ->take(8)
                ->get();
        });

        $visas = Cache::remember('home.visas', 600, function () {
            $visaQuery = Visa::query();
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                $visaQuery->where('is_active', true);
            }
            return $visaQuery
                ->select('id', 'country', 'slug', 'thumbnail', 'type', 'price', 'processing_time')
                ->latest()
                ->take(8)
                ->get();
        });

        $recentPosts = Cache::remember('home.posts', 600, function () {
            $postQuery = Post::latest('published_at');
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                $postQuery->published();
            }
            return $postQuery
                ->select('id', 'title', 'slug', 'image', 'seo_description', 'published_at')
                ->take(3)
                ->get();
        });

        return view('home', compact('packages', 'visas', 'recentPosts'));
    }
}
