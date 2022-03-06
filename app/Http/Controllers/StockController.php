<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {  $extend = $this->extendLayout();
        return view('stock.stock')->with('extend', $extend);
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
         $extend = $this->extendLayout();
       return view('stocklog.stock')->with('extend', $extend);
       
    }

    public function consumption()
    {
         $extend = $this->extendLayout();
      
       return view('consumption.list')->with('extend', $extend);
       
    }

    public function consumptionCreate()
    {
       $extend = $this->extendLayout();
        
        return view('consumption.add')->with('extend', $extend);
    }
}
