<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('product.list');
    }
    public function create()
    {
        return view('product.create');
    }
    public function edit(Request $request)
    {
        $id = $request->query('id');
        return view('product.edits',compact('id'));
    }
}
