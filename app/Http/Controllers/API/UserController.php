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
            //log::info($user->roles) ;
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
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
$input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['name'] =  $user->name;
return response()->json(['success'=>$success], $this-> successStatus); 
    }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details(Request $request) 
    { 
        $user = Auth::user(); 
        $bearerToken = $request->header('Authorization');
        //log::info($bearerToken);
        //return $user->token();
      log::info ( $user->token());


        return response()->json(['success' => $user], $this-> successStatus); 
    } 

    public function logout(Request $request)
    {
     //     $user = Auth::user(); 
    	// return response()->json(['success' => $user]); 
        $user = Auth::user(); 
        $token=$user->token();
        $token->revoke();

        $response = ["message" => "You Have Successfully Logged Out"];
        return response($response,200);
    }
}