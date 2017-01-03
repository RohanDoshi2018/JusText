<?php

	require 'vendor/autoload.php';
	use Parse\ParseClient;
	use Parse\ParseObject;
	use Parse\ParseQuery;
	use Parse\ParseACL;
	use Parse\ParsePush;
	use Parse\ParseUser;
	use Parse\ParseInstallation;
	use Parse\ParseException;
	use Parse\ParseAnalytics;
	use Parse\ParseFile;
	use Parse\ParseCloud;
	ParseClient::initialize('8EFesii3fmXpnFfzhuwf7Wv6ubwLLnlXakfF3Ohn', 'U9H6pPf0gf5kGK9BdhdYG66UBpvLtwbznEawxva5', 'akomFiczJMnD0dtJceHDdFpz5d20OklYg65TSvwU');

	$mode = $_POST;
	
	if(!filter_var($mode['email'], FILTER_VALIDATE_EMAIL)) error(new Exception("INVALID EMAIL"));
	$query = new ParseQuery("Invitation");
	$query->equalTo("email",$mode['email']);
	$results = $query->find();
	if(count($results) == 0) {
		$invitation = new ParseObject("Invitation");
		$invitation->set("email",$mode['email']);
		try {
			$invitation->save();
			print("SAVED!");
		} catch(ParseException $ex) {
			error(new Exception("COULDN'T SAVE"));
		}
	}else
	{
		error(new Exception("EMAIL ALREADY USED"));
	}

	function error($message) {
		header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error', true, 500);
		die($message->getMessage());
	}

?>
