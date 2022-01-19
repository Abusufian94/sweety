<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use DB;
class RetailerController extends Controller
{
    //
    public function retailuserproducts($user_id) {
        try  {
          $user = \DB::table('users')->select('roles as role')->where('id','=',$user_id)->first();
          $products = Product::select('*');
          if($user->role == 2){
            $products = $products->leftJoin('product_retail_assign_log',function($join) {
             $join->on('products.id','=','product_retail_assign_log.product_id');
            })->where('product_retail_assign_log.user_id','=',$user_id);
          }
          $products = $products->get();
          return response()->json(['status'=>true,'message' =>'Products has been fetched successfully','data'=>$products,'error'=>(object)[]]);

        } catch (\Exception $ex) {
            return response()->json(['status'=>true,'message'=>$ex->getMessage(),'date'=>(object)[]]);
        }
    }
}
