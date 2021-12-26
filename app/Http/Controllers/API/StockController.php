<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\User;
use App\Raw;
use App\Consumption;
use App\Product;
use App\Stocklog;
use Illuminate\Support\Facades\Auth;
use Validator;
use Log;
use DataTables;

class StockController extends Controller
{
    public $successStatus = 200;
    /**
     * Stock Insert
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     * path="/api/v1/raw/create",
     * operationId="Insert",
     * tags={"Raw Stock"},
     * summary="Stock Insert",
     * description="Raw Material Insertion",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"raw_name", "unit", "stock","price"},
     *               @OA\Property(property="raw_name", type="text", example="test"),
     *               @OA\Property(property="unit", type="number", example="10"),
     *               @OA\Property(property="stock", type="text", example="test"),
     *               @OA\Property(property="price", type="number", example="1000"),
     *               @OA\Property(property="user_id", type="number", example="5")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *   security={{"bearerAuth":{}}},
     * )
     */
    public function insert(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'raw_name' => 'required|unique:raw_tbl',
            'unit' => 'required',
            'stock' => 'required',
            'price' => "required",
            'user_id'   => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
        }
        $input = $request->all();
        $id = Raw::create($input)->raw_id;
        $this->stock_log($request->all(), $id, "Insert", "Raw");
        return response()->json(['stat' => true, 'message' => "Stock Data created successfully ", 'data' => 'Success'], $this->successStatus);
    }


    /**
     * @OA\patch(
     * path="/api/v1/raw/update",
     * operationId="Update",
     * tags={"Raw Stock"},
     * summary="Stock Update",
     * description="Raw Material Update",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"raw_name", "unit", "stock","price", "user_id", "raw_id"},
     *               @OA\Property(property="raw_id", type="intiger", example="3"),
     *               @OA\Property(property="raw_name", type="text", example="test"),
     *               @OA\Property(property="unit", type="number", example="10"),
     *               @OA\Property(property="stock", type="text", example="test"),
     *               @OA\Property(property="price", type="number", example="1000"),
     *               @OA\Property(property="user_id", type="number", example="5")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{"bearerAuth":{}}},
     * )
     */
    public function updatestock(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'raw_id' => 'required',
            'raw_name' =>  'required|unique:raw_tbl,raw_name,'.$request->raw_id.',raw_id',
            'unit' => 'required',
            'stock' => 'required',
            'price' => "required",
            'user_id'   => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
        }


        $result =  Raw::where('raw_id', $request->raw_id)->first();
        $prev_stock = $result->stock;
        if ($result) {
            $result->raw_name = $request->raw_name;
            $result->unit = $request->unit;
            $result->stock = $request->stock;
            $result->price = $request->price;
            $result->save();

            $this->stock_log($request->all(), $request->raw_id, "Update", "Raw");


            if($prev_stock> $request->stock){
                $data['stock']= $prev_stock - $request->stock;
                $data['unit']=$request->unit;
                $data['user_id'] = $request->user_id;
                $this->consumption($data, $request->raw_id);
            }



            return response()->json(['stat' => true, 'message' => "Updated successfully ", 'data' => "Success"], $this->successStatus);
        } else {
            return response()->json(['stat' => false, 'message' => "Row is not found ", 'data' => []], 404);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/raw/details/{id}",
     *      operationId="getPackageDetail",
     *      tags={"Raw Stock"},
     *      summary="Get list of Raw detail",
     *      description="Returns list of packages detail",
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  security={{"bearerAuth":{}}},
     *  )
     */
    public function getRawDetails(Request $request, $id)
    {
        $package = Raw::where('raw_id', $id)->firstOrFail();
        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => $package,
            'message' => 'Success'
        ]);
    }


     /**
     * @OA\Get(
     *      path="/api/v1/raw/all",
     *      operationId="fetch all",
     *      tags={"Raw Stock"},
     *      summary="Stock list ",
     *      description="Stock list",
     *     @OA\Parameter(
     *      name="search_text",
     *      in="path",
     *      @OA\Schema(
     *           type="text"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="start",
     *      in="path",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="length",
     *      in="path",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="order",
     *      in="path",
     *      @OA\Schema(
     *           type="text"
     *      )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  security={{"bearerAuth":{}}},
     *  )
     */

    public function list(Request $request)
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length"); // total number of rows per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc
            $searchValue = $search_arr['value']; // Search value

            // Total records
            $totalRecords = Raw::select('count(*) as allcount')->count();
            $totalRecordswithFilter = Raw::select('count(*) as allcount')->where('raw_name', 'like', '%' . $searchValue . '%')->count();

            // Get records, also we have included search filter as well
            $records = Raw::orderBy($columnName, $columnSortOrder)
                ->where('raw_tbl.raw_name', 'like', '%' . $searchValue . '%')
                ->orWhere('raw_tbl.unit', 'like', '%' . $searchValue . '%')
                ->orWhere('raw_tbl.price', 'like', '%' . $searchValue . '%')
                ->orWhere('raw_tbl.stock', 'like', '%' . $searchValue . '%')
                ->select('raw_tbl.*')
                ->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = array();

            foreach ($records as $record) {

                $data_arr[] = array(
                    "raw_id" => $record->raw_id,
                    "raw_name" => $record->raw_name,
                    "unit" => $record->unit,
                    "stock" => $record->stock,
                    "price" => $record->price,
                    "status" => $record->status,
                    "status" => $record->status,
                );
            }

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr,
            );

            echo json_encode($response);


        } catch (\Exception $e) {
            Log::info('==================== retailPoListData ======================');
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }


    /**
     * @OA\Delete(
     *      path="/api/v1/raw/delete/{id}",
     *      operationId="Delete",
     *      tags={"Raw Stock"},
     *      summary="Delete ",
     *      description="delete",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={ "user_id"},
     *               @OA\Property(property="user_id", type="number", example="5")
     *            ),
     *        ),
     *    ),
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  security={{"bearerAuth":{}}},
     *  )
     */
    public function deletestock(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'user_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
        }

        $result = Raw::find($id);
        if (!empty($result)) {

            $data = Raw::select('*')
                ->where('raw_id', '=', $id)
                ->get()->toArray();

            $data[0]['user_id'] = $request->user_id;
            $this->stock_log($data[0], $id, "Delete", "Raw");

            $result->delete();
            return response()->json(['stat' => true, 'message' => "data has been removed", "data" => []], 200);
        } else {
            return response()->json(['stat' => true, 'message' => "data Id not found", "data" => []], 404);
        }
    }

    //stock log list

     /**
     * @OA\Get(
     *      path="/api/v1/log/all",
     *      operationId="fetch all",
     *      tags={"Raw Stock"},
     *      summary="Stocklog list ",
     *      description="StockLog list",
     *     @OA\Parameter(
     *      name="search_text",
     *      in="path",
     *      @OA\Schema(
     *           type="text"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="start",
     *      in="path",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="length",
     *      in="path",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="order",
     *      in="path",
     *      @OA\Schema(
     *           type="text"
     *      )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  security={{"bearerAuth":{}}},
     *  )
     */

    // public function log_list(Request $request){
    //     try {
    //         $retailPoList  =  Stocklog::select('*');



    //         if (!empty($search_text)) {

    //             $searchText = $search_text;
    //             $retailPoList  =   $retailPoList->where('raw_name', 'LIKE', "%" . $searchText . "%");
    //         }

    //         $total_count = $retailPoList->count();

    //         if (isset($start) && isset($request['length'])) {

    //             $offset = $start;
    //             $retailPoList = $retailPoList->offset($offset)->limit($request['length']);
    //         }

    //         if (isset($request['order']) && $request['order'] == 'asc')
    //             $retailPoList = $retailPoList->orderBy('raw_stock_log_id', 'asc');
    //         else {
    //             $retailPoList = $retailPoList->orderBy('raw_stock_log_id', 'desc');
    //         }

    //         $retailPoList = $retailPoList->get()->toArray();
    //         // print_r($retailPoList);
    //         // exit();


    //         if ($total_count > 0) {
    //             $retailPoList  = json_decode(json_encode($retailPoList));
    //             $msg = array('status' => 1, 'msg' => 'Success', 'draw' => $request['draw'], 'recordsTotal' => $total_count, 'recordsFiltered' => $total_count,  'data' => $retailPoList);
    //         } else {
    //             $msg = array('status' => 1, 'msg' => 'no data found', 'data' => $retailPoList);
    //         }

    //         return response()->json(["stat" => true, "message" => "list fetch successfully", "data" => $msg], 200);
    //     } catch (\Exception $e) {
    //         Log::info('==================== retailPoListData ======================');
    //         Log::error($e->getMessage());
    //         Log::error($e->getTraceAsString());
    //     }
    // }


    public function log_list(Request $request){
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Stocklog::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Stocklog::select('count(*) as allcount')->where('raw_name', 'like', '%' . $searchValue . '%')->count();

        // Get records, also we have included search filter as well
        $records = Stocklog::orderBy($columnName, $columnSortOrder)
            ->where('raw_stock_log.raw_name', 'like', '%' . $searchValue . '%')
            ->orWhere('raw_stock_log.unit', 'like', '%' . $searchValue . '%')
            ->orWhere('raw_stock_log.price', 'like', '%' . $searchValue . '%')
            ->orWhere('raw_stock_log.log_type', 'like', '%' . $searchValue . '%')
            ->orWhere('raw_stock_log.operation', 'like', '%' . $searchValue . '%')
            ->select('raw_stock_log.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {

            $data_arr[] = array(
                "id" => $record->raw_stock_log_id,
                "raw_name" => $record->raw_name,
                "unit" => $record->unit,
                "stock" => $record->stock,
                "price" => $record->price,
                "log_type" => $record->log_type,
                "operation" => $record->operation,

            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
    }
    //Consumption List
    //stock log list

     /**
     * @OA\Get(
     *      path="/api/v1/consumption/all",
     *      operationId="fetch all",
     *      tags={"Raw Stock"},
     *      summary="Consumption list ",
     *      description="Consumption list",
     *     @OA\Parameter(
     *      name="search_text",
     *      in="path",
     *      @OA\Schema(
     *           type="text"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="start",
     *      in="path",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="length",
     *      in="path",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="order",
     *      in="path",
     *      @OA\Schema(
     *           type="text"
     *      )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  security={{"bearerAuth":{}}},
     *  )
     */

    public function consumption_list(Request $request){
        try {
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length"); // total number of rows per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc
            $searchValue = $search_arr['value']; // Search value

            // Total records
            $totalRecords = Consumption::select('count(*) as allcount')->count();
            $totalRecordswithFilter = Consumption::select('count(*) as allcount')->count();

            // Get records, also we have included search filter as well
            $records = Consumption::orderBy($columnName, $columnSortOrder)
                ->leftjoin('users', 'consumption_tbl.user_id', '=', 'users.id')
                ->leftjoin('raw_tbl', 'consumption_tbl.raw_id', '=', 'raw_tbl.raw_id')
                ->where('raw_tbl.raw_name', 'like', '%' . $searchValue . '%')
                ->orWhere('consumption_tbl.unit', 'like', '%' . $searchValue . '%')
                ->orWhere('consumption_tbl.stock', 'like', '%' . $searchValue . '%')
                ->orWhere('users.name', 'like', '%' . $searchValue . '%')
                ->select('consumption_tbl.*','raw_tbl.raw_name as raw_name','users.name as name')
                ->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = array();

            foreach ($records as $record) {

                $data_arr[] = array(
                    "consumption_id" => $record->consumption_id,
                    "raw_name" => $record->raw->raw_name,
                    "unit" => $record->unit,
                    "stock" => $record->stock,
                    "product_id" => $record->product_id,
                    "name" => $record->users->name

                );
            }

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr,
            );

            //print_r($records);exit();
           echo json_encode($response);

        } catch (\Exception $e) {
            Log::info('==================== retailPoListData ======================');
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }

     // Common Function for updating log table
     public function stock_log($input, $id, $operation, $log)
     {
         $data =  $input;
         $data['raw_id'] = $id;
         $data['operation'] = $operation;
         $data['log_type'] = $log;
         Stocklog::create($data);
     }

      // Common Function for updating Consumption table
      public function consumption($input, $id)
      {

          $data =  $input;
          $data['raw_id'] = $id;
          Consumption::create($data);
      }


    //   Product list
    public function plist(Request $request)
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length"); // total number of rows per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc
            $searchValue = $search_arr['value']; // Search value

            // Total records
            $totalRecords = Product::select('count(*) as allcount')->count();
            $totalRecordswithFilter = Product::select('count(*) as allcount')->where('product_name', 'like', '%' . $searchValue . '%')->count();

            // Get records, also we have included search filter as well
            $records = Product::orderBy($columnName, $columnSortOrder)
                ->where('product.product_name', 'like', '%' . $searchValue . '%')
                ->orWhere('product.product_unit', 'like', '%' . $searchValue . '%')
                ->orWhere('product.product_price', 'like', '%' . $searchValue . '%')
                ->orWhere('product.product_quantity', 'like', '%' . $searchValue . '%')
                ->select('product.*')
                ->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = array();

            foreach ($records as $record) {

                $data_arr[] = array(
                    "id" => $record->id,
                    "product_name" => $record->product_name,
                    "product_image" => $record->product_image,
                    "product_unit" => $record->product_unit,
                    "product_quantity" => $record->product_quantity,
                    "product_price" => $record->product_price,
                    "status" => $record->status,
                    "status" => $record->status,
                );
            }

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr,
            );

            echo json_encode($response);


        } catch (\Exception $e) {
            Log::info('==================== retailPoListData ======================');
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }



     public function rawlist(){
        try {

            $retailPoList  =  Raw::select('*')->where('stock',  '<>' , 0);
            $retailPoList = $retailPoList->orderBy('raw_id', 'desc');
            $total_count = $retailPoList->count();

            $retailPoList = $retailPoList->get()->toArray();

            if ($total_count > 0) {
                $retailPoList  = json_decode(json_encode($retailPoList));
                return response()->json(["stat" => true, "message" => "list fetch successfully", "data" => $retailPoList], 200);
            } else {
                return response()->json(["stat" => true, "message" => "no data found", "data" => $retailPoList], 200);
            }

        } catch (\Exception $e) {
            Log::info('==================== retailPoListData ======================');
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }


    public function productConsumption(Request $request, $id)
    {
        $package = Consumption::with('raw')->where('product_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => $package,
            'message' => 'Success'
        ]);
    }

    public function deleteProduct(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'user_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
        }

        $result = Product::find($id);
        if (!empty($result)) {
            $result->delete();
            return response()->json(['stat' => true, 'message' => "data has been removed", "data" => []], 200);
        } else {
            return response()->json(['stat' => true, 'message' => "data Id not found", "data" => []], 404);
        }
    }

    public function insertProduct(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|unique:product',
            'product_quantity' => 'required',
            'product_unit' => 'required',
            'product_price' => "required",
            'user_id'   => 'required',
            //'product_image'  => 'required|mimes:doc,docx,pdf,txt|max:2048',
        ]);


        if ($validator->fails()) {
            return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
        }

        if ($files = $request->file('product_image')) {
           
            $image = $request->file('product_image');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('documents'), $new_name);
  
        }
        $input = $request->all();

        $input['product_image'] = $new_name;
        $id = Product::create($input)->id;

        if(isset($request->raw_id)&&count($request->raw_id) > 0){
            foreach($request->raw_id as $key=>$attributes)
            {
                $result = $this->raw_update($request->raw_id[$key], $request->stock[$key]);
                if($result ==1 ){
                    $consumption =  new Consumption();
                    $consumption->product_id = $id;
                    $consumption->raw_id = $request->raw_id[$key];
                    $consumption->unit = $request->unit[$key];
                    $consumption->stock = $request->stock[$key];
                    $consumption->user_id = $request->user_id;
                    $consumption->save();
                }
            }
          }
      
        //$this->stock_log($request->all(), $id, "Insert", "Raw");
        return response()->json(['stat' => true, 'message' => "Product Data created successfully ", 'data' => 'Success'], $this->successStatus);
    }

    public function updateProduct(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'product_name' => 'required|unique:product,product_name,'.$request->id.',id',
            'product_quantity' => 'required',
            'product_unit' => 'required',
            'product_price' => "required",
            'user_id'   => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
        }


        $result =  Product::where('id', $request->id)->first();
        if ($files = $request->file('product_image')) {
           
            $image = $request->file('product_image');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('documents'), $new_name);
  
        }
        // $prev_stock = $result->stock;
         if ($result) {
            $result->product_name = $request->product_name;
            $result->product_quantity = $request->product_quantity;
            $result->product_unit = $request->product_unit;
            $result->product_price = $request->product_price;
            $result->product_image = $request->file('product_image')?$new_name: $result->product_image;
            $result->save();

           

            if(isset($request->raw_id)&&count($request->raw_id) > 0){
                foreach($request->raw_id as $key=>$attributes)
                {
                  $consumption = Consumption::where(['product_id'=>$request->id,'raw_id'=>$request->raw_id[$key]])->first();
                  if(empty($consumption))
                  {
                    $result = $this->raw_update($request->raw_id[$key], $request->stock[$key]);
                    if($result ==1 ){
                        $consumption =  new Consumption();
                        $consumption->product_id = $request->id;
                        $consumption->raw_id = $request->raw_id[$key];
                        $consumption->unit = $request->unit[$key];
                        $consumption->stock = $request->stock[$key];
                        $consumption->user_id = $request->user_id;
                        $consumption->save();
                    }
                  }
                  else
                  {
                    $result = $this->raw_update($request->raw_id[$key], $request->stock[$key]);
                    if($result ==1 ){
                        $consumption->product_id = $request->id;
                        $consumption->raw_id = $request->raw_id[$key];
                        $consumption->unit = $request->unit[$key];
                        $consumption->stock = $request->stock[$key];
                        $consumption->user_id = $request->user_id;
                        $consumption->save();
                    }
                  }
                
                }
              }

            return response()->json(['stat' => true, 'message' => "Updated successfully ", 'data' => "Success"], $this->successStatus);
        } else {
            return response()->json(['stat' => false, 'message' => "Row is not found ", 'data' => []], 404);
        }
    }


    public function getProductDetails(Request $request, $id)
    {
        $package = Product::where('id', $id)->firstOrFail();
        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => $package,
            'message' => 'Success'
        ]);
    }

    public function raw_update($id, $quantity){
        
        $result =  Raw::where('raw_id', $id)->first();

        if ($result->stock > $quantity) {
            $result->stock = $result->stock - $quantity;
            $result->save();
            return 1;
        }
    }
}
