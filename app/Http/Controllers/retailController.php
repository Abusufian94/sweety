<?php

namespace App\Http\Controllers;
use App\Services\GuzzleService;
use Illuminate\Http\Request;
use Log;
use App\User;
use Illuminate\Support\Facades\Auth;
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

    public function retailAssignProductList(Request $request)
    {    
        $role = $_COOKIE['loginUser'];
        if($role==1)
        {
             return view('retail.product.retailassign');
        }
        else 
        {
          return view('retail.product.retailassignretail');  
        }
       
    }
    public function billings()
    {
        return view('retail.productBillings.billings');
    }
    public function invoices() {

     
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
        return view('retail.productBillings.invoices')->with('extend', $extend);
        
            
        
    }
    public function invoiceDetails($id) {
       
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
         return view('retail.productBillings.invoiceproduct',compact('id'))->with('extend', $extend)->with('id', $id);

        
            
         
             
       
        
    }
}
