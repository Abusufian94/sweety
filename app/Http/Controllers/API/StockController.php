<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\User;
use App\Raw;
use App\Consumption;
use App\Stocklog;
use Illuminate\Support\Facades\Auth;
use Validator;
use Log;

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
            $retailPoList  =  Raw::where('status', 1);


            if (!empty($search_text)) {

                $searchText = $search_text;
                $retailPoList  =   $retailPoList->where('raw_name', 'LIKE', "%" . $searchText . "%")->orWhere('stock', 'LIKE', "%" . $searchText . "%");
            }

            $total_count = $retailPoList->count();

            if (isset($start) && isset($request['length'])) {

                $offset = $start;
                $retailPoList = $retailPoList->offset($offset)->limit($request['length']);
            }

            if (isset($request['order']) && $request['order'] == 'asc')
                $retailPoList = $retailPoList->orderBy('raw_id', 'asc');
            else {
                $retailPoList = $retailPoList->orderBy('raw_id', 'desc');
            }

            $retailPoList = $retailPoList->get()->toArray();


            if ($total_count > 0) {
                $retailPoList  = json_decode(json_encode($retailPoList));
                $msg = array('status' => 1, 'msg' => 'Success', 'draw' => $request['draw'], 'recordsTotal' => $total_count, 'recordsFiltered' => $total_count,  'data' => $retailPoList);
            } else {
                $msg = array('status' => 1, 'msg' => 'no data found', 'data' => $retailPoList);
            }

            return response()->json(["stat" => true, "message" => "list fetch successfully", "data" => $msg], 200);
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

    public function log_list(Request $request){
        try {
            $retailPoList  =  Stocklog::select('*');

           

            if (!empty($search_text)) {

                $searchText = $search_text;
                $retailPoList  =   $retailPoList->where('raw_name', 'LIKE', "%" . $searchText . "%");
            }

            $total_count = $retailPoList->count();

            if (isset($start) && isset($request['length'])) {

                $offset = $start;
                $retailPoList = $retailPoList->offset($offset)->limit($request['length']);
            }

            if (isset($request['order']) && $request['order'] == 'asc')
                $retailPoList = $retailPoList->orderBy('raw_stock_log_id', 'asc');
            else {
                $retailPoList = $retailPoList->orderBy('raw_stock_log_id', 'desc');
            }

            $retailPoList = $retailPoList->get()->toArray();
            // print_r($retailPoList);
            // exit();


            if ($total_count > 0) {
                $retailPoList  = json_decode(json_encode($retailPoList));
                $msg = array('status' => 1, 'msg' => 'Success', 'draw' => $request['draw'], 'recordsTotal' => $total_count, 'recordsFiltered' => $total_count,  'data' => $retailPoList);
            } else {
                $msg = array('status' => 1, 'msg' => 'no data found', 'data' => $retailPoList);
            }

            return response()->json(["stat" => true, "message" => "list fetch successfully", "data" => $msg], 200);
        } catch (\Exception $e) {
            Log::info('==================== retailPoListData ======================');
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
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

         

            $retailPoList  =  Consumption::with('raw','users')->select('*');

            $total_count = $retailPoList->count();

            if (!empty($search_text)) {

                $searchText = $search_text;
                $retailPoList  =   $retailPoList->where('raw_name', 'LIKE', "%" . $searchText . "%");
            }

            if (isset($start) && isset($request['length'])) {

                $offset = $start;
                $retailPoList = $retailPoList->offset($offset)->limit($request['length']);
            }

            if (isset($request['order']) && $request['order'] == 'asc')
                $retailPoList = $retailPoList->orderBy('consumption_id', 'asc');
            else {
                $retailPoList = $retailPoList->orderBy('consumption_id', 'desc');
            }

            $retailPoList = $retailPoList->get()->toArray();
            // print_r($retailPoList);
            // exit();

           


            if ($total_count > 0) {
                $retailPoList  = json_decode(json_encode($retailPoList));
                $msg = array('status' => 1, 'msg' => 'Success', 'draw' => $request['draw'], 'recordsTotal' => $total_count, 'recordsFiltered' => $total_count,  'data' => $retailPoList);
            } else {
                $msg = array('status' => 1, 'msg' => 'no data found', 'data' => $retailPoList);
            }

            return response()->json(["stat" => true, "message" => "list fetch successfully", "data" => $msg], 200);
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
 
}
