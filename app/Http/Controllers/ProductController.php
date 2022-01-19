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
    //ware house product

    public function warehouseproductlist()
    {
        return view('warehouse.product.list');
    }
    public function warehouseproductedit(Request $request) {
        $id = $request->query('id');
        return view('warehouse.product.edits',compact('id'));
    }

    //assign product to retail
    public function warehouseretaillist()
    {
        return view('warehouse.retail.list');
    }
    public function warehouseretailcreate()
    {
        return view('warehouse.retail.create');
    }
    public function warehouseretailedit(Request $request)
    {
        $id = $request->query('id');
        return view('warehouse.retail.edits',compact('id'));
    }
}
