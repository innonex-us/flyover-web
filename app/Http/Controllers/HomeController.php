<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch featured packages (4x6 grid = 24 items)
        $packages = Package::where('is_active', true)
                           ->latest()
                           ->take(24)
                           ->get();

        return view('home', compact('packages'));
    }
}
