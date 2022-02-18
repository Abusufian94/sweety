<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\Retailproduct;
class RetailerController extends Controller
{
    //
    public function retailuserproducts(Request $request) {
        try  {


          $userData = \Auth::user();
          $products = Product::select('*');

            if($userData->role == 1){
              $products = \DB::table('products')->select('*')->get();
              return response()->json(['status'=>true,'message' =>'Products has been fetched successfully','data'=>$products,'error'=>[]]);
          }

          $userData = \Auth::user();
          $products = Product::select('*');

          if($userData->role == 2){
            $products = $products->innerJoin('product_retail_assign_log',function($join) {
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
    public function suggestiveproducts(Request $request) {
        try {
            $page = !is_null($request->query('page')) ? $request->query('page') : 1;
            $pageSize  = !is_null($request->query('pageSize')) ? $request->query('pageSize') : 10;
            $offsets = ($page-1) * $pageSize;
            $ids = explode(',',$request->query('ids'));
            $productName = $request->query('name');

            $product  = Product::where('status','=',1)->join('retail_product',function($q) use($ids){
                $q->on('product.id','=','retail_product.product_id');
                $q->where('product.status','=',1);
                //$q->whereIn('retail_product.product_id',$ids);
            });
            if(!is_null($productName)) {
                $product = $product->where('product_name', 'LIKE', "%{$productName}%") ;
            }
           if(isset($ids)) {
              $product = $product->whereIn('retail_product.product_id',$ids);
           }
            $product = $product->offset($offsets)->limit($pageSize)->get();
            $resultArray =  (object)['data' =>$product,"meta"=>(object)["page"=>(int)$page,'limit'=>(int)$pageSize]];
            return response()->json(['stat'=>true ,'message'=>"suggestion Listing products has been fetch successfully",'err'=>(object)[],'data'=>$resultArray],200);
        } catch(\Exception $ex) {
            return response()->json(['stat'=>false ,'message'=>"Something went wrong with this api",'err'=>$ex,'data'=>(object)[]],200);
        }

    }


}
