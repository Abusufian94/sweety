<?php

namespace App\Http\Controllers;
//use App\Services\GuzzleService;
use GuzzleHttp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;


class webController extends Controller
{
	 //    private $guzzleService;

  // public function __construct(GuzzleService $guzzleService)
  // {
  //   $this->guzzleService = $guzzleService;
  // }
    public function profile(Request $request)
    {

      $url = 'profile';
      $httpMethod = "GET";

    try {

    	//    $client = new GuzzleHttp\Client();
        //  $res = $client->request('POST', 'http://localhost:8000/sweeterp/public/api/details',
        //   [
        //    'headers' =>
        //         [
  	    //          'Authorization' => 'Bearer ' . $_COOKIE['token'],
  	    //          'Accept' =>'application/json',
  	    //         ]
        //  ]);

    	//    $client = new GuzzleHttp\Client();
        //  $res = $client->request('POST','http://localhost:8080/sweeterp/public/api/details',
        //   [
        //    'headers' =>
        //         [
  	    //          'Authorization' => 'Bearer ' . $_COOKIE['token'],
  	    //          'Accept' =>'application/json',
  	    //         ]
        //  ]);

         return view('admin.profile');
    // }

    }

      catch(\Exception $e)
      {
          die($e->getMessage());
      }

    }

  public function logout()
  {

    $httpMethod = "GET";
     $url = 'logout';
    try {
     // return env('BASE_API_URL').$url;

      $client = new GuzzleHttp\Client();
      $res = $client->request('get', env('BASE_API_URL').$url, [
     'headers' => [
               'Authorization' => 'Bearer ' . $_COOKIE['token'],
               'Accept' =>'application/json',
          ]
              ]);
      return redirect('/login');

    }

    catch(\Exception $e)
    {
          die($e->getMessage());
    }

  }

    public function profile2(Request $request)
    {
     return view('retail.profile2');
    }

    public function profile3(Request $request)
    {
     return view('retail.profile2');
    }


    public function retalUserList(Request $request)
    {
      return "hello";
      return view('retail.retail_user_list');
    }





}

