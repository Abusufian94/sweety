<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ProfileController extends Controller
{
    //
    public function index() {

        $user = Auth::user();
        if($user) {
            return response()->json(['message'=>'profile fetch successfully','data'=>$user],200);
        } else {
            return response()->json(['message'=>'unauthenticated','data'=>(object)[]],401);
        }

    }
}
