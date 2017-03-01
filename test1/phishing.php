<?php
// PHISHING

	require('..\lib\lib.php');//HERE

	// PHISH 1
	// $crawl_id = 11;

	// PHISH 2
	$crawl_id = 12;

	// PHISH 3
	// $crawl_id = 13;

	$env = getEnvironment(ENV,$crawl_id);
	$url = $env->url;
	$path = $env->xpath;
	$doc = $env->documentType;

	//Code to access YQL using PHP
	$yql_query = "select * from ".$doc." where url='".$url."'".(($path == '') ? "" : " and xpath='".$path."'");

	$result = getResultFromYQL(sprintf($yql_query),'store%3A%2F%2Fdatatables.org%2Falltableswithkeys');

	ProcessResult($result,$crawl_id)

?>
