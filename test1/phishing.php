<?php
// PHISHING
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

	// PHISH 1
	// $crawl_id = 11;// JSON

	// PHISH 2
	$crawl_id = 12; // OK

	// PHISH 3
	// $crawl_id = 13; // OK

	$env = getEnvironment(ENV,$crawl_id);
	$result = queryEnvironment($env);
	ProcessResult($result,$crawl_id);

?>
