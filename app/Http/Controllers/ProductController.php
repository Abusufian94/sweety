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
    { $role = $_COOKIE['loginUser'];
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
        //return$extend;
       return view('warehouse.product.list')->with('extend', $extend);
    }
    public function warehouseproductedit(Request $request) {
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
        //return$extend;
          $id = $request->query('id');
            return view('warehouse.product.edits')->with('extend', $extend)->with('id', $id);
    
    }

    //assign product to retail
    public function warehouseretaillist()
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
        return view('warehouse.retail.list')->with('extend', $extend);
    }
    public function warehouseretailcreate()
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
        return view('warehouse.retail.create')->with('extend', $extend);
        
    }
    public function warehouseretailedit(Request $request)
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
         $id = $request->query('id');
        return view('warehouse.retail.edits')->with('extend', $extend)->with('id',$id);
       
       
    }
    //Retail assign Products
    public function retailProducts() {
        $role = $_COOKIE['loginUser'];
        if($role==1)
        {
            
              return view('retail.retails-products.list_admin');
        }
        else 
        {
             return view('retail.retails-products.list');
        }
       
    }
}
