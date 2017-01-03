<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	
	header("Content-Type: text/plain");
	header("Access-Control-Allow-Origin: *");

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
	ParseClient::initialize('oeAOgVV52ypbXGCttQD0EjkT3aYkEyEu6FlE0E6O', 'TEK3rC7AB7eXpaC8UilOs9WPNZYNldrt5enrYBjB', 'yV3aiE8IzVavtmqmR73UqEnRJYwiOUyne6TH023m');

	$number = $_GET['number'];
	print_r("Logs from ".$number."\n\n");

	$query = new ParseQuery("Log");
	$query->equalTo("number",$number);
	$results = $query->find();
	for($i = 0; $i < count($results); $i ++) {
		$result = $results[$i];
		print_r("action: ".$result->get("action")."\n");
		print_r("message: ".$result->get("message")."\n");
		print_r("---\n");
	}

?>
