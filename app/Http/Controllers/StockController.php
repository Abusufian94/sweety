<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        return view('stock.stock');
    }
    public function create()
    {
        return view('stock.create');
    }
    public function edit(Request $request)
    {
        $id = $request->query('id');
        return view('stock.edits',compact('id'));
    }
    public function indexLog()
    {
        return view('stocklog.stock');
    }
}
