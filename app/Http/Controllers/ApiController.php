<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function links()
    {
        $links = Link::query()->with('tags')->get();
        return response()->json($links);
    }
}
