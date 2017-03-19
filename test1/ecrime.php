<?php
// ECRIME PHISHING
	require('..\lib\connect.php');//HERE
	require('..\lib\lib.php');//HERE

/* ====================================================================================================================================
curl -X GET --header "Accept: application/hal+json" "https://api.ecrimex.net/phish?t=316cf83833bf8d08e26329a3bc4b64860cf3b3f4" -k
curl -X GET --header "Accept: application/hal+json" --header "Authorization: 316cf83833bf8d08e26329a3bc4b64860cf3b3f4" "https://api.ecrimex.net/phish" -k
 ====================================================================================================================================*/
	$crawl_id = 15; // ECRIME
	$env = getEnvironment(ENV,$crawl_id);
  $result = getResultFromECrime($env->url,$env->APIKey);
  // echo($result);
	ProcessResult($result,$env->id,$env->url);
?>
