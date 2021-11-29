<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        return view('admin.users.warehouses');
    }
    public function create()
    {
        return view('admin.users.create');
    }


}
