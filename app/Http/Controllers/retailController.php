<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class retailController extends Controller
{
    public function index()
    {
        return view('admin.users.retail_user_list');
    }
    public function create()
    {
        return view('admin.users.create');
    }
    public function edit(Request $request)
    {
        $id = $request->query('id');
        return view('admin.users.edits',compact('id'));
    }
}
