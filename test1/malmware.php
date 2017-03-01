<?php
// MALMWARE

	require('..\lib\lib.php');//HERE

	// MALM 1
	$env = getEnvironment('environment.json',4);

	// MALM 2
	// $env = getEnvironment('environment.json',5);

	// MALM 3
	// $env = getEnvironment('environment.json',6);

	// MALM 4
	// $env = getEnvironment('environment.json',7);

	// MALM 5
	// $env = getEnvironment('environment.json',8);
	$url = $env->url;
	$path = $env->xpath;
	$doc = $env->documentType;


	//Code to access YQL using PHP
	$yql_query = "select * from ".$doc." where url='".$url."'".(($path == '') ? "" : " and xpath='".$path."'");

	$result = getResultFromYQL(sprintf($yql_query),'store%3A%2F%2Fdatatables.org%2Falltableswithkeys');

	echo($result);

//	$r = new stdClass();
//	$r = json_decode($result);
	// $r = new CameraList(json_decode($result));

//	var_dump($r);

?>
