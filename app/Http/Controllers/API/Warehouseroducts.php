<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductLog;
use App\User;
use App\ProductRetailLog;
use App\RetailProduct;
use App\Retail;
use Validator;
use Illuminate\Support\Facades\Log;
use App\RetailUser;

class Warehouseroducts extends Controller
{

  public $successStatus = 200;
  //
  public function updatewarehouseproduct(Request $request)
  {

    try {
      $validator = \Validator::make($request->all(), [
        'product_id' => 'required',
        'product_quantity' => 'required',
      ]);
      if ($validator->fails()) {
        return response()->json(['stat' => false, 'message' => "Please fill the mandatory fields", 'error' => $validator->errors(), "data" => (object)[]], 400);
      }
      $userData = \Auth::user();
      $productId = $request->product_id;
      $quantity = $request->product_quantity;
      $updateProductquantity = Product::find($productId);
      $updateProductquantity->product_quantity = $updateProductquantity->product_quantity + $quantity;
      $updateProductquantity->save();

      ProductLog::create(['product_id' => $updateProductquantity->id, 'quantity' => $request->product_quantity, 'user_id' => $userData->id], 200);
      return response()->json(['stat' => true, "message" => "Product stock has been updated successfully"]);
    } catch (\Exception $ex) {
      return response()->json(["stat" => false, "message" => "something wrong with this api", "data" => (object)[], "error" => (object)[]], 400);
    }
  }

  public function retailUserList(Request $request)
  {
    try {

      $retailPoList  =  Retail::select('*')->where('status',  '=', 1);
      $retailPoList = $retailPoList->orderBy('retail_id', 'desc');


      $total_count = $retailPoList->count();

      $retailPoList = $retailPoList->get()->toArray();

      if ($total_count > 0) {
        $retailPoList  = json_decode(json_encode($retailPoList));
        return response()->json(["stat" => true, "message" => "list fetch successfully", "data" => $retailPoList], 200);
      } else {
        return response()->json(["stat" => true, "message" => "no data found", "data" => $retailPoList], 200);
      }
    } catch (\Exception $e) {
      Log::info('==================== retailUserListData ======================');
      Log::error($e->getMessage());
      Log::error($e->getTraceAsString());
    }
  }

  public function productList(Request $request)
  {
    try {

    

      $productList  =  Product::select('*')->where('status',  '=', 1);
      $productList = $productList->orderBy('id', 'desc');

      $total_count = $productList->count();

      $productList = $productList->get()->toArray();

      if ($total_count > 0) {
        $productList  = json_decode(json_encode($productList));
        return response()->json(["stat" => true, "message" => "list fetch successfully", "data" => $productList], 200);
      } else {
        return response()->json(["stat" => true, "message" => "no data found", "data" => $productList], 200);
      }
    } catch (\Exception $e) {
      Log::info('==================== retailUserListData ======================');
      Log::error($e->getMessage());
      Log::error($e->getTraceAsString());
    }
  }


  public function productRetailLogCreate(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'product_id' => 'required',
      'retail_id' => 'required',
      'quantity' => 'required',
      'unity' => 'required',
      'user_id'   => 'required',
    ]);


    if ($validator->fails()) {
      return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
    }

    $input = $request->all();
    $result = ProductRetailLog::create($input)->product_retail_assign_log_id;
    if ($result) {
      return response()->json(['stat' => true, 'message' => "Product assign to Retail ", 'data' => 'Success'], $this->successStatus);
    } else {
      return response()->json(['stat' => true, 'message' => "Error ", 'data' => 'Success'], $this->successStatus);
    }
  }

  public function productRetailList(Request $request)
  {

      $currentDate = date("Y-m-d");
    

    try {
      $retailUserList  =  ProductRetailLog::with('users', 'retails', 'products')->select('*');
     log::info($request);

      
      if (!empty($request['search']['value'])) {
        $searchText = $request['search']['value'];
        $retailUserList  =   $retailUserList->where(function ($q) use ($searchText) {
          $q->where('unity', 'LIKE', "%" . $searchText . "%")
            ->orWhere('quantity', 'LIKE', "%" . $searchText . "%");
        });
        // ->orWhereHas('products', function($q) use($searchText){
        //   $q->orWhere('product_name', 'LIKE', "%" . $searchText . "%");})
        // ->orWhereHas('users', function($q) use($searchText){
        //     $q->orWhere('name', 'LIKE', "%" . $searchText . "%");})
        //     ->orWhereHas('retails', function($q) use($searchText){
        //       $q->orWhere('name', 'LIKE', "%" . $searchText . "%");});

        $retailUserList->orWhereHas('products', function ($q) use ($searchText) {
          $q->where(function ($q) use ($searchText) {
            $q->orWhere('product_name', 'LIKE', '%' . $searchText . '%');
          });
        });

        $retailUserList->orWhereHas('retails', function ($q) use ($searchText) {
          $q->where(function ($q) use ($searchText) {
            $q->orWhere('retail_name', 'LIKE', '%' . $searchText . '%');
          });
        });
      }

      if(!$request->start_date)
      {
        $retailUserList = $retailUserList->where('updated_at','>=',$currentDate." 00:00:00");
      }

      if($request->start_date)
      {
       
        $retailUserList   = $retailUserList->where('updated_at','>=',date("Y-m-d", strtotime($request->start_date)).' 00:00:00');

      }
            
        if($request->end_date)
            $retailUserList   = $retailUserList->where('updated_at','<=',date("Y-m-d", strtotime($request->end_date)).' 23:59:59');

      
      if($request->status!=null)
      {
          $retailUserList = $retailUserList->where('status', $request->status);
      }

      // $retailUserList = $retailUserList->where('status', 0);

      $total_count = $retailUserList->count();

      if (isset($request['start']) && isset($request['length'])) {

        $offset = $request['start'];
        $retailUserList = $retailUserList->offset($offset)->limit($request['length']);
      }

      $retailUserList = $retailUserList->get()->toArray();




      if ($total_count > 0) {
        $retailUserList  = json_decode(json_encode($retailUserList));

        return response()->json(["stat" => true, "message" => "list fetch successfully", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' => $retailUserList]);
      } else {
        return response()->json(["stat" => true, "message" => "No Records Found", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' => $retailUserList]);
      }
    } catch (\Exception $e) {
      Log::info('==================== warehouselist ======================');
      Log::error($e->getMessage());
      return response()->json(["stat" => true, "message" => $e->getMessage(), "data" => []], 400);
      Log::error($e->getTraceAsString());
    }
  }

  public function getProductRetailDetails(Request $request, $id)
  {
    $package = ProductRetailLog::with('products')->where('product_retail_assign_log_id', $id)->firstOrFail();


    return response()->json([
      'status' => 'success',
      'status_code' => '200',
      'data' => $package,
      'message' => 'Success'
    ]);
  }

  public function productRetailLogUpdate(Request $request)
  {

    // dd($request->all());
    // exit();

    $validator = Validator::make($request->all(), [
      'id' => 'required',
      'product_id' => 'required',
      'retail_id' => 'required',
      'quantity' => 'required',
      'unity' => 'required',
      'user_id'   => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
    }


    $result =  ProductRetailLog::where('product_retail_assign_log_id', $request->id)->first();

    // print_r($result);
    // exit();
    if ($result) {
      $result->product_id = $request->product_id;
      $result->quantity = $request->quantity;
      $result->unity = $request->unity;
      $result->retail_id = $request->retail_id;
      $result->user_id = $request->user_id;
      $result->save();

      return response()->json(['stat' => true, 'message' => "Updated successfully ", 'data' => "Success"], $this->successStatus);
    } else {
      return response()->json(['stat' => false, 'message' => "Row is not found ", 'data' => []], 404);
    }
  }

  public function assignedPendingTotalStock(Request $request)
  {
      try {
           

           $retailProductAssign = \DB::table('product_retail_assign_log')->where('product_retail_assign_log.product_id', $request->product_id)->where('product_retail_assign_log.status',0)->leftJoin('retail_tbl', 'retail_tbl.retail_id','=','product_retail_assign_log.retail_id')->selectRaw("product_retail_assign_log.product_id, product_retail_assign_log.retail_id,product_retail_assign_log.quantity,product_retail_assign_log.unity, retail_tbl.retail_name")->get();
           if(count($retailProductAssign)>0)
             {
              return response()->json(['stat' => true, 'message' => "Pending products", 'data' => $retailProductAssign]);
             }
          else
             {
                return response()->json(['stat' => false, 'message' => "no products found", 'data' => []]);
             }
      } catch (\Exception $e) {
      Log::info('==================== assignedPendingTotalStock ======================');
      Log::error($e->getMessage());
      return response()->json(["stat" => true, "message" => $e->getMessage(), "data" => []], 400);
      Log::error($e->getTraceAsString());
    }
  }


   public function retailAssignedProductList(Request $request)
  {
  
    //    log::info($request);
        try {
           $currentDate = date("Y-m-d");
          $userData = \Auth::user();
          $orderBy = $request->order[0]['dir'];

          $assignRetailId = RetailUser::where('user_id', $userData->id)->select('retail_id')->first();

      $retailAssignProuct  =  ProductRetailLog::with('users', 'retails', 'products')->select('*');

      if (!empty($request['search']['value'])) {
        $searchText = $request['search']['value'];
        $retailAssignProuct  =   $retailAssignProuct->where(function ($q) use ($searchText) {
          $q->where('unity', 'LIKE', "%" . $searchText . "%")
            ->orWhere('quantity', 'LIKE', "%" . $searchText . "%");
        });
        // ->orWhereHas('products', function($q) use($searchText){
        //   $q->orWhere('product_name', 'LIKE', "%" . $searchText . "%");})
        // ->orWhereHas('users', function($q) use($searchText){
        //     $q->orWhere('name', 'LIKE', "%" . $searchText . "%");})
        //     ->orWhereHas('retails', function($q) use($searchText){
        //       $q->orWhere('name', 'LIKE', "%" . $searchText . "%");});

        $retailAssignProuct->orWhereHas('products', function ($q) use ($searchText) {
          $q->where(function ($q) use ($searchText) {
            $q->orWhere('product_name', 'LIKE', '%' . $searchText . '%');
          });
        });

        $retailAssignProuct->orWhereHas('retails', function ($q) use ($searchText) {
          $q->where(function ($q) use ($searchText) {
            $q->orWhere('retail_name', 'LIKE', '%' . $searchText . '%');
          });
        });
      }
       if(!empty($assignRetailId))
             {
                $assignRetailId = $assignRetailId->retail_id;
                $retailAssignProuct = $retailAssignProuct->where('retail_id',$assignRetailId);
             }

    
       if(!$request->start_date)
      {
        $retailAssignProuct = $retailAssignProuct->where('updated_at','>=',$currentDate." 00:00:00");
      }

      if($request->start_date)
      {
       
        $retailAssignProuct   = $retailAssignProuct->where('updated_at','>=',date("Y-m-d", strtotime($request->start_date)).' 00:00:00');

      }
            
        if($request->end_date)
            $retailAssignProuct   = $retailAssignProuct->where('updated_at','<=',date("Y-m-d", strtotime($request->end_date)).' 23:59:59');
            if($request->status!=null){
          $retailAssignProuct = $retailAssignProuct->where('status', $request->status);
      }

      // $retailUserList = $retailUserList->where('status', 0);

      $total_count = $retailAssignProuct->count();

      if (isset($request['start']) && isset($request['length'])) {

        $offset = $request['start'];
        $retailAssignProuct = $retailAssignProuct->offset($offset)->limit($request['length']);
      }
      log::info($retailAssignProuct->toSql());

      $retailAssignProuct = $retailAssignProuct->get()->toArray();




      if ($total_count > 0) {
        $retailAssignProuct  = json_decode(json_encode($retailAssignProuct));

        return response()->json(["stat" => true, "message" => "list fetch successfully", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' => $retailAssignProuct]);
      } else {
        return response()->json(["stat" => true, "message" => "No Records Found", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' => $retailAssignProuct]);
      }
    } 
    catch (\Exception $e) {
      Log::info('==================== retailAssignedProductList ======================');
      Log::error($e->getMessage());
      return response()->json(["stat" => true, "message" => $e->getMessage(), "data" => []], 400);
      Log::error($e->getTraceAsString());
    }
    

  }

}
