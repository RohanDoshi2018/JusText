<?php


$text = "Hello my name is Juan.";
$clientID = "JusText";
$clientSecret = "uIPSg1j+YY92TzW8dPHfrPgryy0xq6bg0CnKXJVfYxw=";

$rootUri = 'https://api.datamarket.azure.com/Bing/Search';

error_reporting(~0);
ini_set('display_errors', 1);

searchNews('Gaza');

function searchNews($query){
		$acctKey = '1hwk+/F29FQV2FN87DBxgsNkq9V40Pl8/7mHcenMwBc=';
		$url = 'https://api.datamarket.azure.com/Bing/Search/News?$format=json&Query=' . urlencode("'$query'");
		$auth = base64_encode("$acctKey:$acctKey");
		$data = array(
			'http' => array(
			'request_fulluri' => true,
			// ignore_errors can help debug – remove for production. This option added in PHP 5.2.10
			'ignore_errors' => true,
			'header' => "Authorization: Basic $auth")
			);
		$context = stream_context_create($data);
		//var_dump($data);
		//echo $context;
		// Get the response from Bing.
		//$response = file_get_contents($url, 0, $context);
		$response = file_get_contents($url, 0, $context);

		//print_r($response);
		echo $response;

		$result = json_decode($response,true)['d']['results'];

		$finalresult = array();
		for($i = 0; $i < count($result); $i ++) {
			$entry = $result[$i];
			array_push($finalresult,array($entry['Title'],$entry['Description']));
		}

}



?>
