<?php
// ECRIME PHISHING
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

	//======================================================= test

	// $crawl_id = 9; // BOTNET 1 - OK
	//
	// // $crawl_id = 10; // BOTNET 2 - OK
	//
	// $env = getEnvironment(ENV,$crawl_id);
	// $result = queryEnvironment($env);
	// ProcessResult($result,$crawl_id,$env->url);
	//======================================================= actual
	// $environments = getEnvironments(ENV,CAT_BOTNET);
	// foreach ($environments as $env){
	// 	$result = queryEnvironment($env);
	// 	ProcessResult($result,$env->id,$env->url);
	// }

/* ====================================================================================================================================
curl -X GET --header "Accept: application/hal+json" "https://api.ecrimex.net/phish?t=316cf83833bf8d08e26329a3bc4b64860cf3b3f4" -k
curl -X GET --header "Accept: application/hal+json" --header "Authorization: 316cf83833bf8d08e26329a3bc4b64860cf3b3f4" "https://api.ecrimex.net/phish" -k
 ====================================================================================================================================*/

	$crawl_id = 15; // ECRIME
	$env = getEnvironment(ENV,$crawl_id);
  $result = getResultFromECrime($env->url,$env->APIKey);

  // echo($result);
	ProcessResult($result,$env->id,$env->url);

  function getResultFromECrime($url,$key) {
      $session = curl_init($url);
			curl_setopt($session, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: ' . $key));
      $json = curl_exec($session);
      curl_close($session);
      return $json;
  }

?>
