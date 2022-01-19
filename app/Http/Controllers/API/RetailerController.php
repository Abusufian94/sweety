<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
class RetailerController extends Controller
{
    //
    public function retailuserproducts(Request $request) {
        try  {
          $userData = \Auth::user();
          $products = Product::select('*');
          if($userData->role == 2){
            $products = $products->leftJoin('product_retail_assign_log',function($join) {
             $join->on('products.id','=','product_retail_assign_log.product_id');
            })->where('product_retail_assign_log.user_id','=',$userData->id);
          }
         $total_count = $products->count();
         if (isset($request['start']) && isset($request['length'])) {

           $offset = $request['start'];
           $retailProductList = $products->offset($offset)->limit($request['length']);
         }
         $retailProductList = $products->get()->toArray();
         if ($total_count > 0) {
           $retailProductList  = json_decode(json_encode($retailProductList));
           return response()->json(["stat" => true, "message" => "list fetch successfully", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' => $retailProductList]);
         } else {
           return response()->json(["stat" => true, "message" => "No Records Found", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' => $retailProductList]);
         }
        } catch (\Exception $e) {
            Log::info('==================== Retailer Product ======================');
            Log::error($e->getMessage());
            return response()->json(["stat" => true, "message" => $e->getMessage(), "data" => []], 400);
            Log::error($e->getTraceAsString());
        }
    }
}
