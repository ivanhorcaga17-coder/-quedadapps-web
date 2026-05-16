<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class DescargarController extends Controller
{
    public function index(): View
    {
        $reviews = rescue(
            fn () => Review::with('user')->latest()->get(),
            new Collection,
            report: false,
        );

        return view('frontend.descargar', compact('reviews'));
    }
}
