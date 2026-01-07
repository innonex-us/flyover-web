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
        return view('visas.show', compact('visa'));
    }
}
