<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch featured packages (for now just latest 6)
        $packages = Package::where('is_active', true)
                           ->latest()
                           ->take(6)
                           ->get();

        return view('home', compact('packages'));
    }
}
