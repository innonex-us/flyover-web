<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visa;

class VisaController extends Controller
{
    public function index(Request $request)
    {
        $query = Visa::query();

        if ($request->has('search')) {
            $query->where('country', 'like', '%' . $request->search . '%');
        }

        $visas = $query->paginate(12);

        return view('visas.index', compact('visas'));
    }

    public function show($slug)
    {
        $visa = Visa::where('slug', $slug)->firstOrFail();

        // SEO Data
        $title = $visa->country . ' Visa Processing - ' . $visa->type . ' | FlyoverBD';
        $meta_description = \Illuminate\Support\Str::limit(strip_tags($visa->description), 155);
        $meta_image = $visa->thumbnail 
            ? (\Illuminate\Support\Str::startsWith($visa->thumbnail, 'http') ? $visa->thumbnail : \Illuminate\Support\Facades\Storage::url($visa->thumbnail))
            : asset('logo.png');

        return view('visas.show', compact('visa', 'title', 'meta_description', 'meta_image'));
    }
}
