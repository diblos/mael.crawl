<?php
// SPAM

	require('..\lib\lib.php');//HERE

	// SPAM 1
	$url = "https://myip.ms/browse/blacklist/Blacklist_IP_Blacklist_IP_Addresses_Live_Database_Real-time";
	$path = "//*[@id=\"blacklist_tbl\"]";

	//Code to access YQL using PHP
	$yql_query = "select * from html where url='".$url."/' and xpath='".$path."'";

	$result = getResultFromYQL(sprintf($yql_query),'store%3A%2F%2Fdatatables.org%2Falltableswithkeys');

	echo($result);

//	$r = new stdClass();
//	$r = json_decode($result);
	// $r = new CameraList(json_decode($result));

//	var_dump($r);

?>
