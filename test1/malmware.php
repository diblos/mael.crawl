<?php
// MALMWARE

	require('..\lib\lib.php');//HERE

	// MALM 1
	// $crawl_id = 4;

	// MALM 2
	// $crawl_id = 5;

	// MALM 3
	// $crawl_id = 6;

	// MALM 4
	// $crawl_id = 7;

	// MALM 5
	$crawl_id = 8;

	$env = getEnvironment(ENV,$crawl_id);
	$url = $env->url;
	$path = $env->xpath;
	$doc = $env->documentType;

	//Code to access YQL using PHP
	$yql_query = "select * from ".$doc." where url='".$url."'".(($path == '') ? "" : " and xpath='".$path."'");

	$result = getResultFromYQL(sprintf($yql_query),'store%3A%2F%2Fdatatables.org%2Falltableswithkeys');

	ProcessResult($result,$crawl_id)

?>
