<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Log;

class UserController extends Controller
{
    public $successStatus = 200;
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     * path="/api/login",
     * operationId="authLogin",
     * tags={"user"},
     * summary="User Login",
     * description="Login User Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email", example="admin@gmail.com"),
     *               @OA\Property(property="password", type="password", example="bc420")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=401, description="UnAuthorised"),
     * )
     */

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['role'] = $user->roles;
            $success['id'] = $user->id;
            $success['name'] = $user->name;
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

     /**
        * @OA\Post(
        * path="/api/register",
        * operationId="Register",
        * tags={"user"},
        * summary="User Register",
        * description="User Register here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name","email", "password", "roles", "password_confirmation"},
        *               @OA\Property(property="name", type="text", example="sharvari"),
        *               @OA\Property(property="email", type="text", example="sharvari@gmail.com"),
        *               @OA\Property(property="password", type="password", example="123456789"),
        *               @OA\Property(property="password_confirmation", type="password", example="123456789"),
        *               @OA\Property(property="roles", type="number", example="1")
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
        * )
        */

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'roles' => "required"
            //'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $updatePassword = User::findOrFail($user->id);
        $updatePassword->password_as = $request->password;
        $updatePassword->status = 1;
        $updatePassword->save();
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
        return response()->json(['stat' => true, 'message' => "User account has been created successfully ", 'data' => $success], $this->successStatus);
    }



     /**
 * @OA\Get(path="/api/v1/profile",
 *   tags={"user"},
 *   summary="Get the details of an authenticated user",
 *   description="",
 *   operationId="getAuthUser",
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\Schema(type="string"),
 *     @OA\Header(
 *       header="X-Rate-Limit",
 *       @OA\Schema(
 *           type="integer",
 *           format="int32"
 *       ),
 *       description="calls per hour allowed by the user"
 *     ),
 *     @OA\Header(
 *       header="X-Expires-After",
 *       @OA\Schema(
 *          type="string",
 *          format="date-time",
 *       ),
 *       description="date in UTC when token expires"
 *     )
 *   ),
 *   @OA\Response(response=400, description="Error xXx"),
 *   security={{"bearerAuth":{}}},
 * )
 */
    public function myprofile()
    {
        $user  = Auth::user();
        $response_array = [
            'name' => $user->name,
            'email' => $user->email,
            "role" => $user->roles,
            'avator' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png'
        ];
        return response()->json(['stat' => true, 'message' => "user profile has been fetch succsssfully", "data" => $response_array]);
    }

    public function updatewarehouse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'w_id' => 'required',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
        ]);
        if ($validator->fails()) {
            return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
        }
        try {
            $user = User::where('roles', 3)->where('id', $request->w_id)->first();
            if ($user) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->password_as = $request->password;
                $user->status = $request->status;
                $user->save();
                $success['token'] =  $user->createToken('MyApp')->accessToken;
                $success['name'] =  $user->name;
                return response()->json(['stat' => true, 'message' => "User account has been created successfully ", 'data' => $success], $this->successStatus);
            } else {
                return response()->json(['stat' => false, 'message' => "Ware house id is not found ", 'data' => []], 404);
            }
        } catch (\Exception $ex) {
            return response()->json(["stat" => false, "message" => "somthing wrong"], 400);
        }
    }
    public function warehouselist(Request $request)
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
            $totalRecords = User::where('users.roles','=', 3)->select('count(*) as allcount')->count();
            $totalRecordswithFilter = User::select('count(*) as allcount')->where('users.roles','=', 3)->where('name', 'like', '%' . $searchValue . '%')->count();

            // Get records, also we have included search filter as well
            $records = User::where('users.roles','=', 3)->orderBy($columnName, $columnSortOrder)
                ->where('users.name', 'like', '%' . $searchValue . '%')
                ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                ->select('users.*')
                ->skip($start)
                ->take($rowperpage)
                ->where('users.roles','=', 3)
                ->get();

            $data_arr = array();

            foreach ($records as $record) {

                $data_arr[] = array(
                    "id" => $record->id,
                    "name" => $record->name,
                    "email" => $record->email,
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

   public function editwarehouse($id)
   {
     $warehosue = User::find($id);
     return response()->json(['stat'=>true,'message'=>'get warehouse details','data'=>$warehosue]);
   }

   public function deletewarehouse(Request $request)
   {
        $validator = Validator::make($request->all(), [
            'w_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
        }
        $user = User::find($request->w_id);
        //echo"<pre>";print_r($user);exit;
        if (!empty($user)) {
            $user = $user->delete();
            return response()->json(['stat' => true, 'message' => "Warehouse has been removed", "data" => []], 200);
        } else {
            return response()->json(['stat' => true, 'message' => "Warehouse Id not found", "data" => []], 404);
        }
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
        $token = $user->token();
        $token->revoke();
        $response = ['stat' => true, "message" => "You Have Successfully Logged Out", 'data' => []];
        return response($response, 200);
    }
}
