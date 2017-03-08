<?php
// BOTNET
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

	//======================================================= test
	$crawl_id = 9; // BOTNET 1 - OK

	// $crawl_id = 10; // BOTNET 2 - OK

	$env = getEnvironment(ENV,$crawl_id);
	$result = queryEnvironment($env);
	ProcessResult($result,$crawl_id);
	//======================================================= actual
	// $environments = getEnvironments(ENV,CAT_BOTNET);
	// foreach ($environments as $env){
	// 	$result = queryEnvironment($env);
	// 	ProcessResult($result,$env->id);
	// }
?>
