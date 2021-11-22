<?php

namespace App\Services;
use GuzzleHttp\Client as GuzzleHttpClient;
use Session;

class GuzzleService
{
	public function guzzleHttp($url,$httpMethod,$dataArray){

		$requestContent = $dataArray;

        $client = new GuzzleHttpClient();
      	$apiRequest = $client->request($httpMethod, $url, $requestContent);
      	
      	return $response = json_decode($apiRequest->getBody());
	}

	public function http($url,$httpMethod,$request,$dataType='multipart')
	{
		//$data=$this->generateData($request,$dataType);
		$url = env('BASE_API_URL').'/'.$url;

		 $requestContent = [
	     'headers' => [
	            'Accept' => 'application/json',
	        ],
	        $dataType => $data,
	      ];

        $client = new GuzzleHttpClient();
      	$apiRequest = $client->request($httpMethod, $url, $requestContent);
      	return $response = json_decode($apiRequest->getBody());
	}

	public function httpAuth($url,$httpMethod,$request,$dataType='multipart')
	{
		//$data=$this->generateData($request,$dataType);
		$url = env('BASE_API_URL').$url;

		 $requestContent = [
	     'headers' => [
	             'Authorization' => 'Bearer ' . $_COOKIE['token'],
	        ],
	        $dataType => $request,
	      ];

        $client = new GuzzleHttpClient();
      	$apiRequest = $client->request($httpMethod, $url, $requestContent);
      	return $response = json_decode($apiRequest->getBody());
	}

	

}
