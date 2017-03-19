<?php
	// DEFACEMENT
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

//======================================================= test
	// // $crawl_id = 1; // DEFACE 1 - NOT OK
	//
	// // $crawl_id = 2; // DEFACE 2 - OK
	//
	// $crawl_id = 3; // DEFACE 3 - NOT OK
	//
	// $env = getEnvironment(ENV,$crawl_id);
	// $result = queryEnvironment($env);
	// ProcessResult($result,$crawl_id,$env->url);
	//======================================================= actual
	$environments = getEnvironments(ENV,CAT_DEFACEMENT);
	foreach ($environments as $env){
		$result = queryEnvironment($env);
		ProcessResult($result,$env->id,$env->url);
	}
?>
