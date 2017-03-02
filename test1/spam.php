<?php
// SPAM
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

	// SPAM 1
	$crawl_id = 14; // OK

	$env = getEnvironment(ENV,$crawl_id);
	$result = queryEnvironment($env);
	ProcessResult($result,$crawl_id);

?>
