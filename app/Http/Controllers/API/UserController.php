<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\RetailUser;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Log;

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
           if($user->status==0)
           {
              return response()->json(['error' => 'Account Deactivated: Please contact Admin'], 403);
           }
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
          $checkUser= User::where('email', $request['email'])->first();
        // if(!empty($checkUser))
        // {
        //      return response()->json(['stat' => true, 'message' => "The email has already been taken!", 'data' => $success]);
        // }
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

     public function retailUserCreate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'retail_id'=>'required',
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
        $retailInput=[];
        $rtailInput['retail_id']=$request['retail_id'];
        $rtailInput['user_id']=$updatePassword->id;
        log::info($rtailInput);
        $retailUser = RetailUser::create($rtailInput);

        return response()->json(['stat' => true, 'message' => "Retail account has been created successfully ", 'data' => $success], $this->successStatus);
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
            $retailUserList  = \DB::table('users')->selectRaw("users.name,users.email,users.status,users.password_as, users.id");

             if (!empty($request['search']['value']))
             {
                     $searchText = $request['search']['value'];
                    $retailUserList  =   $retailUserList->where(function($q) use($searchText) {
                        $q->where('users.name', 'LIKE', "%" . $searchText . "%")
                        ->orWhere('users.email', 'LIKE', "%" . $searchText . "%");});


                }
            $retailUserList = $retailUserList->where('roles', 3);

            $total_count = $retailUserList->count();

             if (isset($request['start']) && isset($request['length'])) {

                $offset = $request['start'];
                $retailUserList = $retailUserList->offset($offset)->limit($request['length']);
            }

            $retailUserList = $retailUserList->get()->toArray();




            if ($total_count > 0) {
                $retailUserList  = json_decode(json_encode($retailUserList));

                return response()->json(["stat" => true, "message" => "list fetch successfully", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' =>$retailUserList]);
            } else {
                return response()->json(["stat" => true, "message" => "No Records Found", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' =>$retailUserList]);
            }


        } catch (\Exception $e) {
            Log::info('==================== warehouselist ======================');
            Log::error($e->getMessage());
              return response()->json(["stat" => true, "message" => "Something went wrong", "data" => []], 400);
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
        try {
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

        } catch (Exception $e) {
            Log::info('==================== deletewarehouse ======================');
            Log::error($e->getMessage());
              return response()->json(["stat" => true, "message" => "Something went wrong", "data" => []], 400);
            Log::error($e->getTraceAsString());
        }


    }

      public function retailUserDelete(Request $request)
   {
       try {
              $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['stat' => false, 'message' => "Please fill the mendatory fields", 'error' => $validator->errors(), "data" => []], 400);
        }
        $user = User::find($request->user_id);

        $ret_user = \DB::table('retail_user')->where('user_id',$request->user_id)->get();
        $retail_user_id=$ret_user[0]->retail_user_id;
        $retail_user=RetailUser::find($retail_user_id);


        //echo"<pre>";print_r($user);exit;
        if (!empty($user)) {
            $user = $user->delete();
            $retail_user->delete();

            return response()->json(['stat' => true, 'message' => "Retail User has been removed", "data" => []], 200);
        } else {
            return response()->json(['stat' => true, 'message' => "Retail Id not found", "data" => []], 404);
        }

            } catch (Exception $e) {
                 Log::info('==================== retailUserDelete ======================');
            Log::error($e->getMessage());
              return response()->json(["stat" => true, "message" => "Something went wrong", "data" => []], 400);
            Log::error($e->getTraceAsString());

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

    public function retailUsers(Request $request)
    {

       try {
            $retailUserList  = \DB::table('users')->leftJoin('retail_user', 'retail_user.user_id', '=', 'users.id')->leftJoin('retail_tbl','retail_tbl.retail_id','=','retail_user.retail_id')->selectRaw("users.id,users.name,users.email,users.status,users.password_as, retail_tbl.retail_name");

             if (!empty($request['search']['value']))
             {
                     $searchText = $request['search']['value'];
                    $retailUserList  =   $retailUserList->where(function($q) use($searchText) {
                        $q->where('users.name', 'LIKE', "%" . $searchText . "%")
                        ->orWhere('users.email', 'LIKE', "%" . $searchText . "%");});


                }
            $retailUserList = $retailUserList
                        ->where('users.roles', 2);

            $total_count = $retailUserList->count();

             if (isset($request['start']) && isset($request['length'])) {

                $offset = $request['start'];
                $retailUserList = $retailUserList->offset($offset)->limit($request['length']);
            }

            $retailUserList = $retailUserList->get()->toArray();




            if ($total_count > 0) {
                $retailUserList  = json_decode(json_encode($retailUserList));

                return response()->json(["stat" => true, "message" => "list fetch successfully", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' =>$retailUserList]);
            } else {
                return response()->json(["stat" => true, "message" => "No Data Found", "draw" => intval($request['draw']), "recordsTotal" => $total_count, "recordsFiltered" =>  $total_count, 'data' =>$retailUserList]);
            }


        } catch (\Exception $e) {
            Log::info('==================== Retail User List Data ======================');
            Log::error($e->getMessage());
              return response()->json(["stat" => true, "message" => "Something went wrong", "data" => []], 400);
            Log::error($e->getTraceAsString());
        }


    }

        function changeUserStatus(Request $request)
    {
     try {

        

         $warehosueUser = User::find($request->id);
         
         if($warehosueUser->status==0)
         {
            $warehosueUser->status=1;
         }
         else if($warehosueUser->status==1)
         {
            $warehosueUser->status=0;
         }
        // log::info($warehosueUser);
         $warehosueUser->save();
        return response()->json(["stat" => true, "message" => "Status Changed Successfully"]);


     } catch (\Exception $e) {
           Log::info('==================== warehoseUserStatus ======================');
            Log::error($e->getMessage());
              return response()->json(["stat" => true, "message" => "Something went wrong", "data" => $e->getMessage()], 400);
            Log::error($e->getTraceAsString());
     }
    }
}
