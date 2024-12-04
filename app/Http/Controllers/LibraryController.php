<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Library', [
            'userRole' => $request->user()->role,
        ]);
    }
}
