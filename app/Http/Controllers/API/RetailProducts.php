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


      $retailProduct = RetailProduct::where(['product_retail_id' => $request->retail_product_id])->first();
      if ($retailProduct) {
        $retailProduct->product_status = $request->product_status;
        $retailProduct->save();
        
        if($request->product_status ==1){

            $product =  Product::where('id', $retailProduct->product_id)->first();
            $product->product_quantity =  $product->product_quantity - $retailProduct->quantity;
            $product->save();
            if($product){
                $result =  ProductRetailLog::where('product_retail_assign_log_id', $request->product_retail_id)->first();
                $result->status = $request->product_status;
                $result->user_id = $request->user_id;
                $result->quantity =  $product->product_quantity - $retailProduct->quantity;
                $result->save();

            }

        }

      return response()->json(['stat' => true, 'message' => "Updated successfully ", 'data' => "Success"], $this->successStatus);
    } else {
      return response()->json(['stat' => false, 'message' => "Row is not found ", 'data' => []], 404);
    }
  }

}
