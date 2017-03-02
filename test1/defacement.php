<?php
	// DEFACEMENT
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

	// DEFACE 1
	// $crawl_id = 1; // NOT OK

	// DEFACE 2
	$crawl_id = 2; // OK

	// DEFACE 3
	// $crawl_id = 3; // NOT OK

	$env = getEnvironment(ENV,$crawl_id);
	$result = queryEnvironment($env);
	ProcessResult($result,$crawl_id);

?>
