<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductLog;
use App\User;
use App\ProductRetailLog;
use Validator;
use Illuminate\Support\Facades\Log;
use App\RetailProduct;

class RetailProducts extends Controller
{

  public $successStatus = 200;
  //

  public function RetailProducts(Request $request)
  {

    try {
      $retailProductList  =  RetailProduct::with('users', 'retails', 'products')->select('*');

      if (!empty($request['search']['value'])) {
        $searchText = $request['search']['value'];
        $retailProductList  =   $retailProductList->where(function ($q) use ($searchText) {
          $q->where('unity', 'LIKE', "%" . $searchText . "%")
            ->orWhere('quantity', 'LIKE', "%" . $searchText . "%");
        });
        $retailProductList->orWhereHas('products', function ($q) use ($searchText) {
          $q->where(function ($q) use ($searchText) {
            $q->orWhere('product_name', 'LIKE', '%' . $searchText . '%');
          });
        });

        $retailProductList->orWhereHas('retails', function ($q) use ($searchText) {
          $q->where(function ($q) use ($searchText) {
            $q->orWhere('name', 'LIKE', '%' . $searchText . '%');
          });
        });
      }

      $total_count = $retailProductList->count();

      if (isset($request['start']) && isset($request['length'])) {

        $offset = $request['start'];
        $retailProductList = $retailProductList->offset($offset)->limit($request['length']);
      }

      $retailProductList = $retailProductList->get()->toArray();




      if ($total_count > 0) {
        $retailProductList  = json_decode(json_encode($retailProductList));

        return response()->json(["stat" => true, "message" => "list fetch successfully", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' => $retailProductList]);
      } else {
        return response()->json(["stat" => true, "message" => "No Records Found", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' => $retailProductList]);
      }
    } catch (\Exception $e) {
      Log::info('==================== warehouselist ======================');
      Log::error($e->getMessage());
      return response()->json(["stat" => true, "message" => $e->getMessage(), "data" => []], 400);
      Log::error($e->getTraceAsString());
    }
  }

  public function approveProduct(Request $request)
  {
   
      //Check For entry Retail Product Table


      $ProductRetailLog = ProductRetailLog::where(['product_retail_assign_log_id' => $request->product_retail_assign_log_id])->first();
      if ($ProductRetailLog) {
      
        
        if($request->product_status ==1){

            $product =  Product::where('id', $ProductRetailLog->product_id)->first();
            $product->product_quantity =  $product->product_quantity - $ProductRetailLog->quantity;
            $product->save();
            if($product){
              $retailProduct = new RetailProduct();
              $retailProduct->product_id = $ProductRetailLog->product_id;
              $retailProduct->retail_id = $ProductRetailLog->retail_id;
              $retailProduct->unit = $ProductRetailLog->unity;
              $retailProduct->product_status =$request->product_status;
              $retailProduct->quantity = $ProductRetailLog->quantity;
              $retailProduct->user_id = $ProductRetailLog->user_id;
              $retailProduct->save();

            }

        }

        $ProductRetailLog->status = $request->product_status;
        $ProductRetailLog->save();

      return response()->json(['stat' => true, 'message' => "Updated successfully ", 'data' => "Success"], $this->successStatus);
    } else {
      return response()->json(['stat' => false, 'message' => "Row is not found ", 'data' => []], 404);
    }
  }

}
