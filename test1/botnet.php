<?php
// BOTNET

	require('..\lib\lib.php');//HERE

	// BOTNET 1
	// $url = "https://zeustracker.abuse.ch/rss.php";
	// $path = "";

	// BOTNET 2
	$url = "http://www.malwaredomainlist.com/hostslist/mdl.xml";
	$path = "";

	//Code to access YQL using PHP
	// $yql_query = "select * from html where url='".$url."/' and xpath='".$path."'";
	// $yql_query = "select * from rss where url='".$url."'";// and xpath='".$path."'";
	$yql_query = "select * from xml where url='".$url."'";// and xpath='".$path."'";

	$result = getResultFromYQL(sprintf($yql_query),'store%3A%2F%2Fdatatables.org%2Falltableswithkeys');

	echo($result);

//	$r = new stdClass();
//	$r = json_decode($result);
	// $r = new CameraList(json_decode($result));

//	var_dump($r);

?>
