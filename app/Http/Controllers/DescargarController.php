<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class DescargarController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user')->latest()->get();

        return view('frontend.descargar', compact('reviews'));
    }
}
