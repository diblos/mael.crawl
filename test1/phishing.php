<?php
// PHISHING

	require('..\lib\lib.php');//HERE

	// PHISH 1
	// $url = "http://data.phishtank.com/data/56e517c2fa8e04f21ea60a00a46e7e5a4f1823ff050542432a489a5c0202503d/online-valid.json";
	// $path = "";

	// PHISH 2
	// $url = "https://openphish.com";
	// $path = "//*[@id=\'wrap\']/div[1]/table";

	// PHISH 3
	$url = "http://hosts-file.net/?s=Browse&f=PSH";
	$path = "/html/body/table[2]/tbody/tr/td/table/tbody/tr[3]/td/table[2]";

	//Code to access YQL using PHP
	$yql_query = "select * from html where url='".$url."/' and xpath='".$path."'";

	$result = getResultFromYQL(sprintf($yql_query),'store%3A%2F%2Fdatatables.org%2Falltableswithkeys');

	echo($result);

//	$r = new stdClass();
//	$r = json_decode($result);
	// $r = new CameraList(json_decode($result));

//	var_dump($r);

?>
