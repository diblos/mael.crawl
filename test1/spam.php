<?php
// SPAM

	require('..\lib\lib.php');//HERE

	// SPAM 1
	$crawl_id = 14;

	$env = getEnvironment(ENV,$crawl_id);
	$result = queryEnvironment($env);
	ProcessResult($result,$crawl_id);

?>
