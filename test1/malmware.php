<?php
// MALMWARE
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

//======================================================= test
	// // $crawl_id = 4; // MALM 1 - OK
	//
	// // $crawl_id = 5; // MALM 2 - OK
	//
	// $crawl_id = 6; // MALM 3 - OK
	//
	// // $crawl_id = 7; // MALM 4 - OK
	//
	// // $crawl_id = 8; // MALM 5 - OK
	//
	// $env = getEnvironment(ENV,$crawl_id);
	// $result = queryEnvironment($env);
	// ProcessResult($result,$crawl_id,$env->url);
	//======================================================= actual
	$environments = getEnvironments(ENV,CAT_MALMWARE);
	foreach ($environments as $env){
		$result = queryEnvironment($env);
		ProcessResult($result,$env->id,$env->url);
	}

?>
