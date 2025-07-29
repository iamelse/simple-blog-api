<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string']);
        $data['slug'] = Str::slug($data['name']);
        return Category::create($data);
    }
}
