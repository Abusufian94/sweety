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
        return view('admin.users.retail_user_create');
    }
    public function edit(Request $request)
    {
        $id = $request->query('id');
        return view('admin.users.edits',compact('id'));
    }

    public function retailProductList()
    {
        return view('retail.product.list');
    }

    public function retailAssignProductList()
    {
        return view('retail.product.retailassign');
    }
    public function billings()
    {
        return view('retail.productBillings.billings');
    }
}
