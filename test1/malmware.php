<?php
// MALMWARE
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

	// MALM 1
	$crawl_id = 4; // OK - db not OK

	// MALM 2
	// $crawl_id = 5; // OK - db not OK

	// MALM 3
	// $crawl_id = 6; // OK - db not OK

	// MALM 4
	// $crawl_id = 7; // OK - db not OK

	// MALM 5
	// $crawl_id = 8; // OK

	$env = getEnvironment(ENV,$crawl_id);
	$result = queryEnvironment($env);
	ProcessResult($result,$crawl_id);

?>
