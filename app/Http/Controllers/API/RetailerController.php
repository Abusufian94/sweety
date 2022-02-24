<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\Retailproduct;
use App\RetailUser;
use Log;
class RetailerController extends Controller
{
    //
    public function retailuserproducts(Request $request) {
        try  {


          $userData = \Auth::user();
          $orderBy = $request->order[0]['dir'];

          $assignRetailId = RetailUser::where('user_id', $userData->id)->select('retail_id')->first();


          $retailProduct = \DB::table('retail_product')->leftJoin('product', 'retail_product.product_id', '=','product.id' )->leftJoin('retail_tbl', 'retail_tbl.retail_id', '=', 'retail_product.retail_id')->selectRaw("product.id,product.product_name,product.product_image,product.product_price,product.product_unit,retail_product.quantity,retail_tbl.retail_name,retail_tbl.street_name");

          $retailProduct=$retailProduct->where('product.status', 1);

             if (!empty($request['search']['value']))
             {
                $searchText = $request['search']['value'];
                $retailProduct  =   $retailProduct->where(function ($q) use ($searchText)
                {
                  $q->where('retail_product.quantity', 'LIKE', "%" . $searchText . "%")
                  ->orWhere('product.product_name', 'LIKE', "%" . $searchText . "%")
                  ->orWhere('retail_tbl.retail_name', 'LIKE', "%" . $searchText . "%")
                  ->orWhere('retail_tbl.street_name', 'LIKE', "%" . $searchText . "%");
                 });
             }

             if(!empty($assignRetailId))
             {
                $assignRetailId = $assignRetailId->retail_id;
                $retailProduct = $retailProduct->where('retail_product.retail_id',$assignRetailId);
             }

             if(isset($request['start']) && isset($request['length']))
             {
               $offset = $request['start'];
               $retailProduct = $retailProduct->offset($offset)->limit($request['length']);
             }

          $total_count = $retailProduct->count();
          $retailProduct = $retailProduct->orderBy('product.product_name', $orderBy)->get()->toArray();

          log::info($retailProduct);



          return response()->json(["stat" => true, "message" => "No Records Found", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' => $retailProduct]);

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
            $ids = $request->query('ids') ? explode(',',$request->query('ids')): null;
            $productName = $request->query('name');
            $product = (object)[];
            $product  = Product::select('product.*')->where('status','=',1)->join('retail_product',function($q) use($ids){
                $q->on('product.id','=','retail_product.product_id');
                $q->where('product.status','=',1);
                //$q->whereIn('retail_product.product_id',$ids);
            });
            if(isset($productName)) {
                $product = $product->where('product.product_name', 'LIKE', "%{$productName}%");
            }
            if(isset($ids)) {
                $product = $product->whereIn('product.id', $ids);
            }

            $product = $product->offset($offsets)->limit($pageSize)->get();
            $resultArray =  (object)['data' =>$product,"meta"=>(object)["page"=>(int)$page,'limit'=>(int)$pageSize]];
            return response()->json(['stat'=>true ,'message'=>"suggestion Listing products has been fetch successfully",'err'=>(object)[],'data'=>$resultArray],200);
        } catch(\Exception $ex) {
            return response()->json(['stat'=>false ,'message'=>"Something went wrong with this api",'err'=>$ex,'data'=>(object)[]],200);
        }

    }

}
