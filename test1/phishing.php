<?php
// PHISHING
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

//======================================================= test
	// // $crawl_id = 11;//PHISH 1 - JSON
	//
	// // $crawl_id = 12; // PHISH 2 - OK
	//
	// $crawl_id = 13; // PHISH 3 - OK
	//
	// $env = getEnvironment(ENV,$crawl_id);
	// $result = queryEnvironment($env);
	// ProcessResult($result,$crawl_id,$env->url);
//======================================================= actual
$environments = getEnvironments(ENV,CAT_PHISHING);
foreach ($environments as $env){
	$result = queryEnvironment($env);
	ProcessResult($result,$env->id,$env->url);
}
?>
