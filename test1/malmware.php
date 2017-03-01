<?php
// MALMWARE
	require('..\lib\connect.php');//HERE
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
	$result = queryEnvironment($env);
	ProcessResult($result,$crawl_id);

?>
