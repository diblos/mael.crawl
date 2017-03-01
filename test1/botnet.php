<?php
// BOTNET
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

	// BOTNET 1
	$crawl_id = 9;

	// BOTNET 2
	// $crawl_id = 10;

	$env = getEnvironment(ENV,$crawl_id);
	$result = queryEnvironment($env);
	ProcessResult($result,$crawl_id);

?>
