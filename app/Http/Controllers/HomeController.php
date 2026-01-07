<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Visa;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch featured packages (4x6 grid = 24 items)
        $packages = Package::where('is_active', true)
                           ->latest()
                           ->take(8)
                           ->get(); // Limit to 8 for cleaner home page, full list in /tours

        // Fetch active visas
        $visaQuery = Visa::query();
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            $visaQuery->where('is_active', true);
        }
        $visas = $visaQuery->latest()->take(8)->get();

        // Fetch recent blog posts
        $postQuery = Post::latest('published_at');
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            $postQuery->published();
        }
        $recentPosts = $postQuery->take(3)->get();

        return view('home', compact('packages', 'visas', 'recentPosts'));
    }
}
