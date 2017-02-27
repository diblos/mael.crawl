<?php
	// DEFACEMENT

	require('..\lib\lib.php');//HERE

	// MALM 1
	$url = "http://www.zone-h.org/archive/special=1";
	$path = "//*[@id=\"ldeface\"]";

	// MALM 2
	// $url = "http://www.hack-mirror.com/attacks.html";
	// $path = "/html/body/div[4]/div[1]/div[10]/div[5]/div/div/div/div[2]/table";

	// MALM 3
	// $url = "http://zone-hc.org/archive-1.html";
	// $path = "";

	//Code to access YQL using PHP
	$yql_query = "select * from html where url='".$url."' and xpath='".$path."'";

	$result = getResultFromYQL(sprintf($yql_query),'store%3A%2F%2Fdatatables.org%2Falltableswithkeys');

	echo($result);

//	$r = new stdClass();
//	$r = json_decode($result);
	// $r = new CameraList(json_decode($result));

//	var_dump($r);

?>
