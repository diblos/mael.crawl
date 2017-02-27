<?php
// MALMWARE

	require('..\lib\lib.php');//HERE

	// MALM 1
	// $url = "http://malc0de.com/database/";
	// $path = "/html/body/font/center/table";

	// MALM 2
	// $url = "http://vxvault.net/ViriList.php";
	// $path = "//*[@id=\"page\"]/table";

	// MALM 3
	// $url = "http://hosts-file.net/?s=Browse&f=EXP";
	// $path = "/html/body/table[2]/tbody/tr/td/table/tbody/tr[3]/td/table[2]";

	// MALM 4
	// $url = "http://hosts-file.net/?s=Browse&f=EMD";
	// $path = "/html/body/table[2]/tbody/tr/td/table/tbody/tr[3]/td/table[2]";

	// MALM 5
	$url = "http://www.malwaredomainlist.com/mdl.php";
	$path = "//*[@id=\"content_box\"]/div/div[3]/table";


	//Code to access YQL using PHP
	$yql_query = "select * from html where url='".$url."' and xpath='".$path."'";

	$result = getResultFromYQL(sprintf($yql_query),'store%3A%2F%2Fdatatables.org%2Falltableswithkeys');

	echo($result);

//	$r = new stdClass();
//	$r = json_decode($result);
	// $r = new CameraList(json_decode($result));

//	var_dump($r);

?>
