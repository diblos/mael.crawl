<?php
// SPAM
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

//======================================================= test
	// $crawl_id = 14; // SPAM 1 - OK
	//
	// $env = getEnvironment(ENV,$crawl_id);
	// $result = queryEnvironment($env);
	// ProcessResult($result,$crawl_id);
//======================================================= actual
$environments = getEnvironments(ENV,CAT_SPAM);
foreach ($environments as $env){
	$result = queryEnvironment($env);
	ProcessResult($result,$env->id);
}
?>
