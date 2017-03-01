<?php
	// DEFACEMENT

	require('..\lib\lib.php');//HERE

	// DEFACE 1
	// $env = getEnvironment('environment.json',1);

	// DEFACE 2
	$env = getEnvironment('environment.json',2);

	// DEFACE 3
	// $env = getEnvironment('environment.json',3);
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
