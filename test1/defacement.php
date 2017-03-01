<?php
	// DEFACEMENT

	require('..\lib\lib.php');//HERE

	// DEFACE 1
	// $crawl_id = 1;

	// DEFACE 2
	$crawl_id = 2;

	// DEFACE 3
	// $crawl_id = 3;

	$env = getEnvironment(ENV,$crawl_id);
	$result = queryEnvironment($env);
	ProcessResult($result,$crawl_id);

?>
