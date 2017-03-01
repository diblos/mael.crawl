<?php
	// DEFACEMENT

	require('..\lib\lib.php');//HERE

	// DEFACE 1
	// $crawl_id = 1;

	// DEFACE 2
	$crawl_id = 2;

	// DEFACE 3
	// $crawl_id = 3;

	$env = getEnvironment(ENV,$crawl_id);
	$url = $env->url;
	$path = $env->xpath;
	$doc = $env->documentType;

	//Code to access YQL using PHP
	$yql_query = "select * from ".$doc." where url='".$url."'".(($path == '') ? "" : " and xpath='".$path."'");

	$result = getResultFromYQL(sprintf($yql_query),'store%3A%2F%2Fdatatables.org%2Falltableswithkeys');

	ProcessResult($result,$crawl_id)

?>
