<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductLog;

class Warehouseroducts extends Controller
{
    //
    public function updatewarehouseproduct(Request $request) {

      try{
        $validator = \Validator::make($request->all(), [
            'product_id' => 'required',
            'product_quantity' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['stat' => false, 'message' => "Please fill the mandatory fields", 'error' => $validator->errors(), "data" =>(object)[]], 400);
        }
        $userData = \Auth::user();
        $productId = $request->product_id;
        $quantity = $request->product_quantity;
        $updateProductquantity = Product::find($productId);
        $updateProductquantity->product_quantity = $updateProductquantity->product_quantity + $quantity;
        $updateProductquantity->save();

        ProductLog::create(['product_id'=>$updateProductquantity->id, 'quantity'=>$request->product_quantity, 'user_id'=>$userData->id],200);
        return response()->json(['stat'=>true,"message"=>"Product stock has been updated successfully"]);
      } catch(\Exception $ex){
          return response()->json(["stat"=>false, "message"=>"something wrong with this api","data"=>(object)[],"error"=>(object)[]],400);
      }
    }
}
