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
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
             $success['role']= $user->roles;
            return response()->json(['success' => $success], $this-> successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
/**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'roles'=>"required"
            //'c_password' => 'required|same:password',
        ]);
      if ($validator->fails()) {
            return response()->json(['stat'=>false,'message'=>"Please fill the mendatory fields",'error'=>$validator->errors(),"data"=>[]], 400);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $updatePassword = User::findOrFail($user->id);
        $updatePassword->password_as = $request->password;
        $updatePassword->status = 1;
        $updatePassword->save();
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        $success['name'] =  $user->name;
       return response()->json(['stat'=>true,'message'=>"User account has been created successfully ",'data'=>$success], $this-> successStatus);
    }
    public function myprofile()
    {
        $user  = Auth::user();
        $response_array = [
            'name'=>$user->name,
            'email'=>$user->email,
            "role"=>$user->roles,
            'avator'=>'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png'
        ];
        return response()->json(['stat'=>true,'message'=>"user profile has been fetch succsssfully","data"=>$response_array]);
    }

   public function updatewarehouse(Request $request)
   {
    $validator = Validator::make($request->all(), [
        'w_id' => 'required'
    ]);
    if ($validator->fails()) {
            return response()->json(['stat'=>false,'message'=>"Please fill the mendatory fields",'error'=>$validator->errors(),"data"=>[]], 400);
        }
        try{
            $user = User::where('roles',3)->where('id',$request->w_id)->first();
            if($user)
            {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password=bcrypt($request->password);
                $user->password_as = $request->password;
                $user->status = $request->status;
                $user->save();
                $success['token'] =  $user->createToken('MyApp')-> accessToken;
                $success['name'] =  $user->name;
                return response()->json(['stat'=>true,'message'=>"User account has been created successfully ",'data'=>$success], $this-> successStatus);
            }
            else
            {
                return response()->json(['stat'=>false,'message'=>"Ware house id is not found ",'data'=>[]], 404);
            }

        }
        catch(\Exception $ex){
            return response()->json(["stat"=>false,"message"=>"somthing wrong"],400);
        }


   }
   public function warehouselist(Request $request)
   {
    try
    {
       // $retailId = $request['retail_id'];


        $retailPoList  = User::where('roles',3);


        if (!empty($request['search_text'])) {

            $searchText = $request['search_text'];
            $retailPoList  =   $retailPoList->where('name', 'LIKE', "%" . $searchText . "%")->orWhere('email','LIKE',"%".$searchText."%");

        }

        $total_count = $retailPoList->count();

        if (isset($request['start']) && isset($request['length'])) {

            $offset = $request['start'];
            $retailPoList = $retailPoList->offset($offset)->limit($request['length']);
        }

        if(isset($request['order']) && $request['order']=='asc')
            $retailPoList = $retailPoList->orderBy('id', 'asc');
        else
        {
            $retailPoList = $retailPoList->orderBy('id', 'desc');
        }

        $retailPoList = $retailPoList->get()->toArray();


        if ($total_count > 0) {
            $retailPoList  = json_decode(json_encode($retailPoList));
            $msg = array('status' => 1, 'msg' => 'Success', 'draw' => $request['draw'], 'recordsTotal' => $total_count, 'recordsFiltered' => $total_count,  'data' => $retailPoList);
        } else {
            $msg = array('status' => 1, 'msg' => 'no data found', 'data' => $retailPoList);
        }

        return response()->json(["stat"=>true,"message"=>"Ware house list fetch successfully","data"=>$msg],200);
    }
    catch(\Exception $e)
    {
        Log::info('==================== retailPoListData ======================');
        Log::error($e->getMessage());
        Log::error($e->getTraceAsString());
    }
   }

   public function deletewarehouse(Request $request)
   {
        $validator = Validator::make($request->all(), [
            'w_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['stat'=>false,'message'=>"Please fill the mendatory fields",'error'=>$validator->errors(),"data"=>[]], 400);
        }
        $user=User::find($request->w_id);
        //echo"<pre>";print_r($user);exit;
        if(!empty($user))
        {
            $user=$user->delete();
            return response()->json(['stat'=>true,'message'=>"Warehouse has been removed","data"=>[]],200);
        }
        else
        {
            return response()->json(['stat'=>true,'message'=>"Warehouse Id not found","data"=>[]],404);
        }
   }
    public function logout(Request $request)
    {
        $user = Auth::user();
        $token=$user->token();
        $token->revoke();
        $response = ['stat'=>true,"message" => "You Have Successfully Logged Out",'data'=>[]];
        return response($response,200);
    }
}
