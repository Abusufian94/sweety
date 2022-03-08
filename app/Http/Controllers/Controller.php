<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


/**
 * @OA\Info(
 *    title="Your super  ApplicationAPI",
 *    version="1.0.0",
 * ),
* @OA\SecurityScheme(
     *    securityScheme="bearerAuth",
     *    in="header",
     *    name="bearerAuth",
     *    type="http",
     *    scheme="bearer",
     * ),
 */



class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

 public function extendLayout()
 {
 	 $role = $_COOKIE['loginUser'];
        if($role==1)
        {
           $extend="admin";
        }
         if($role==2)
        {
           $extend="retailer";
        }
         if($role==3)
        {
           $extend="warehouse";
        }
       return $extend;
 }

}