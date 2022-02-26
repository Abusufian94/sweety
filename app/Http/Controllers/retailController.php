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
}
