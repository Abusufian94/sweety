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
          $role = $_COOKIE['loginUser'];
        if($role==1)
        {
           $extend="admin";
        }
         if($role==2)
        {
           $extend="retailer";
        }
         if($role==3)
        {
           $extend="warehouse";
        }
      
       return view('stock.stock')->with('extend', $extend);
       
    }

    public function consumption()
    {
         $role = $_COOKIE['loginUser'];
        if($role==1)
        {
           $extend="admin";
        }
         if($role==2)
        {
           $extend="retailer";
        }
         if($role==3)
        {
           $extend="warehouse";
        }
      
       return view('consumption.list')->with('extend', $extend);
       
    }

    public function consumptionCreate()
    {
        $role = $_COOKIE['loginUser'];
        if($role==1)
        {
           $extend="admin";
        }
         if($role==2)
        {
           $extend="retailer";
        }
         if($role==3)
        {
           $extend="warehouse";
        }
        
        return view('consumption.add')->with('extend', $extend);
    }
}
