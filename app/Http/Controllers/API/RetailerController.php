<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\Retailproduct;
use App\RetailUser;
use App\Invoice;
use App\SoldProduct;
use App\Retail;
use Log;
use PDF;
use File;
class RetailerController extends Controller
{
    //
    public function retailuserproducts(Request $request) {
        try  {


          $userData = \Auth::user();
          $orderBy = $request->order[0]['dir'];

          $assignRetailId = RetailUser::where('user_id', $userData->id)->select('retail_id')->first();


          $retailProduct = \DB::table('retail_product')->leftJoin('product', 'retail_product.product_id', '=','product.id' )->leftJoin('retail_tbl', 'retail_tbl.retail_id', '=', 'retail_product.retail_id')->selectRaw("product.id,product.product_name,product.product_image,product.product_price,product.product_unit,retail_product.quantity,retail_tbl.retail_name,retail_tbl.street_name, retail_tbl.retail_name,retail_tbl.street_name");

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
            $userData = \Auth::user();
            $assignRetailId = RetailUser::where('user_id', $userData->id)->select('retail_id')->first();
            $page = !is_null($request->query('page')) ? $request->query('page') : 1;
            $pageSize  = !is_null($request->query('pageSize')) ? $request->query('pageSize') : 10;
            $offsets = ($page-1) * $pageSize;
            $ids = !is_null($request->query('ids'))?explode(',',$request->query('ids')): null;
            $productName = $request->query('name');

            $product  = Product::where('status','=',1)->join('retail_product',function($q) use($ids){
                $q->on('product.id','=','retail_product.product_id');
                $q->where('product.status','=',1);
                //$q->whereIn('retail_product.product_id',$ids);
            });
            if(isset($productName)) {
                $product = $product->where('product_name', 'LIKE', "%{$productName}%") ;
            }
           if(isset($ids)) {
              $product = $product->whereIn('retail_product.product_id',$ids);
           }
           if(!empty($assignRetailId))
           {
              $product = $product->where('retail_product.retail_id','=',$assignRetailId->retail_id);
           }
            //$product = $product->whereIn('retail_product.user_id','=',$userData->id);
            $product = $product->offset($offsets)->limit($pageSize)->get();
            $resultArray =  (object)['data' =>$product,"meta"=>(object)["page"=>(int)$page,'limit'=>(int)$pageSize]];
            return response()->json(['stat'=>true ,'message'=>"suggestion Listing products has been fetch successfully",'err'=>(object)[],'data'=>$resultArray],200);
        } catch(\Exception $ex) {
            Log::info('==================== Retailer billing ======================');
            Log::error($ex->getMessage());
            return response()->json(['stat'=>false ,'message'=>"Something went wrong with this api",'err'=>$ex,'data'=>(object)[]],200);
        }

    }
    public function saveBilling(Request $request) {
   try {
    $validator = \Validator::make($request->all(), [
        'totalPrice' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
    }
    $payload = [];
    $userData = \Auth::user();
    $products = $request->input('products');
    $retailUser = RetailUser::where('user_id',$userData->id)->first();
    $product = json_decode($products,true);
    if (is_array($product)) {
    $invoices = new Invoice();
    $invoices->invoice_number = 'Inv'.rand(10000,99999).time();
    $invoices->retail_id = $retailUser->retail_id;
    $invoices->user_id = $userData->id;
    $invoices->payment_method = $request->paymentMethod;
    $invoices->total_price = $request->totalPrice;
   $invoices->save();
     foreach($product as $item){
         $soldProducts = new SoldProduct();
         $soldProducts->invoice_id = $invoices->id;
         $soldProducts->product_id = $item['product_id'];
         $soldProducts->retail_id = $retailUser->retail_id;
         $soldProducts->quantity = $item['quantity'];
         $soldProducts->unit = $item['unit'];
         $soldProducts->price = $item['price'];
         $soldProducts->save();
         $retailProduct = Retailproduct::where('product_id','=',$item['product_id'])->first();
         $retailProduct->quantity = $retailProduct->quantity - $item['quantity'];
         $retailProduct->save();
         $url = $this->genaratePdf($retailUser->retail_id, $soldProducts->invoice_id, $product);
     }

    return response()->json(['message'=>"Billing Details has been save Successfully",'stat'=>true,"error"=>[],'data'=>$url]);
    } else {
        return response()->json(['message'=>"Please provide sold Product in arrays of object format",'stat'=>true,"error"=>[],'data'=>[]]);
    }


   } catch (\Exception $ex) {
       return response()->json(['msg'=>'error','stat'=>false, 'error'=>$ex->getMessage(), "data"=>[]]);
   }
}

public function invoiceList(Request $request) {
    try {
        log::info($request);
        $currentDate = date("Y-m-d");
    $userData = \Auth::user();
    //$orderBy = $request->order[0]['dir'];
    $assignRetailId = RetailUser::where('user_id', $userData->id)->select('retail_id')->first();


     $invoice = \DB::table('invoice')->leftJoin('retail_tbl', 'retail_tbl.retail_id', '=', 'invoice.retail_id')->leftJoin('users', 'users.id', '=','invoice.user_id');
     $invoice = $invoice->selectRaw("invoice.id as id, invoice.invoice_number,invoice.payment_method, invoice.total_price,invoice.updated_at, users.name, retail_tbl.retail_name ");
     

     $invoice = \App\Invoice::query();
   // ->selectRaw("invoice.id as id, invoice.invoice_number,invoice.payment_method, invoice.total_price,invoice.updated_at");

       if(!empty($request['search']['value']))
        {
            $searchText = $request['search']['value'];
            $invoice  =   $invoice->where(function ($q) use ($searchText)
            {
              $q->where('invoice.invoice_number', 'LIKE', "%" . $searchText . "%")
                ->orWhere('invoice.total_price', 'LIKE', "%" . $searchText . "%")
                 ->orWhere('retail_tbl.retail_name', 'LIKE', "%" . $searchText . "%")
                 ->orWhere('users.name', 'LIKE', "%" . $searchText . "%")
                 ;
             });
        }
        if(!is_null( $assignRetailId ))
        {
            $invoice  =   $invoice->where('invoice.retail_id', $assignRetailId ->retail_id);
        }
        if(!$request->start_date)
      {
        $invoice = $invoice->where('invoice.updated_at','>=',$currentDate." 00:00:00");
      }

      if($request->start_date)
      {

        $invoice   = $invoice->where('invoice.updated_at','>=',date("Y-m-d", strtotime($request->start_date)).' 00:00:00');

      }

        if($request->end_date)
            $invoice   = $invoice->where('invoice.updated_at','<=',date("Y-m-d", strtotime($request->end_date)).' 23:59:59');

     if($request->status!=null){
                $invoice = $invoice->where('invoice.payment_method', $request->status);
            }

        if(isset($request['start']) && isset($request['length']))
        {
          $offset = $request['start'];
          $invoice = $invoice->offset($offset)->limit($request['length']);
        }
        $total_count = $invoice->count();
        if(isset($request->order[0]['dir']))
        {
            $invoice = $invoice->orderBy('invoice.id',$request->order[0]['dir']);
        }
        $invoice = $invoice->get()->toArray();
        return response()->json(["stat" => true, "message" => "Success", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' =>$invoice]);
    }
    catch (\Exception $ex) {
        Log::info('==================== Retailer billing ======================');
        Log::error($ex->getMessage());
        return response()->json(['msg'=>'error','stat'=>false, 'error'=>$ex->getMessage(), "data"=>[]]);
    }
 }


 public function soldProduct(Request $request,$id) {
    try  {


      $userData = \Auth::user();
     // $orderBy = $request->order[0]['dir'];

      $assignRetailId = RetailUser::where('user_id', $userData->id)->select('retail_id')->first();


      $retailProduct = \DB::table('invoice')->leftJoin('sold_product', 'sold_product.invoice_id', '=','invoice.id' )->leftJoin('product', 'sold_product.product_id', '=','product.id' )->leftJoin('retail_tbl', 'retail_tbl.retail_id', '=', 'sold_product.retail_id')->selectRaw("invoice.id as invoiceId, invoice.invoice_number, product.id,product.product_name,product.product_image,product.product_price,product.product_unit,sold_product.quantity,retail_tbl.retail_name,retail_tbl.street_name");

    //  $retailProduct=$retailProduct->where('product.status', 1);
      $retailProduct=$retailProduct->where('sold_product.invoice_id', $id);

         if (!empty($request['search']['value']))
         {
            $searchText = $request['search']['value'];
            $retailProduct  =   $retailProduct->where(function ($q) use ($searchText)
            {
              $q->where('sold_product.quantity', 'LIKE', "%" . $searchText . "%")
              ->orWhere('product.product_name', 'LIKE', "%" . $searchText . "%")
              ->orWhere('retail_tbl.retail_name', 'LIKE', "%" . $searchText . "%")
              ->orWhere('retail_tbl.street_name', 'LIKE', "%" . $searchText . "%");
             });
         }

         if(!empty($assignRetailId))
         {
            $assignRetailId = $assignRetailId->retail_id;
            $retailProduct = $retailProduct->where('sold_product.retail_id',$assignRetailId);
         }

         if(isset($request['start']) && isset($request['length']))
         {
           $offset = $request['start'];
           $retailProduct = $retailProduct->offset($offset)->limit($request['length']);
         }

      $total_count = $retailProduct->count();
      if(isset( $request->order[0]['dir']))
      {
        $retailProduct=$retailProduct->orderBy('product.product_name', $request->order[0]['dir']);
      }

      $retailProduct = $retailProduct->get()->toArray();

     



      return response()->json(["stat" => true, "message" => "No Records Found", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' => $retailProduct]);

    } catch (\Exception $e) {
        Log::info('==================== Sold Product ======================');
        Log::error($e->getMessage());
        return response()->json(["stat" => true, "message" => $e->getMessage(), "data" => []], 400);
        Log::error($e->getTraceAsString());



    }
}
    public function genaratePdf($retail_id,$invoice_id,$products) {
        $storeDetail = Retail::where('retail_id',$retail_id)->first();
        $invoiceDetails = Invoice::where('id',$invoice_id)->first();


    $data = [
    'Retailer_name' => $storeDetail->retail_name,
    'Invoice_No'=>$invoiceDetails->invoice_number,
    'total_price'=>$invoiceDetails->total_price,
    'sold_product'=>$products,
     ];


     $filename = "invoice_".rand(100000,99999).time().'.pdf';
     $path = public_path().'/invoices/';
     if(!File::exists($path)) {
        File::makeDirectory($path);
    }
    $update = Invoice::find($invoice_id);
    $update->file =  $filename;
    $update->save();
    $pdf = PDF::loadView('retail.productBillings.invoiceTemplate', $data)->save($path.$filename);
    return  asset('invoices/'.$filename);//response()->download($path.$filename, null, [], null);

    }
}
