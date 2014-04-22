<?php

$key = "9c132d31-6a30-4cac-8d8b-8a1970834799"; // supplied by PTV
$developerId = 2; // supplied by PTV

$date = gmdate('Y-m-d\TH:i:s\Z');
$healthcheckurl = "/v2/healthcheck?timestamp=" . $date;
$nearmeurl = "/v2/nearme/latitude/-37.7989769/longitude/144.919174";
$stopsurl = "/v2/mode/2/line/783/stops-for-line";
$generalurl = "/v2/mode/2/stop/23806/departures/by-destination/limit/1";
$specificurl = "/v2/mode/2/line/783/stop/23806/directionid/46/departures/all/limit/1";

?>
<h1>Health Check</h1>
<? 
$signedUrl = generateURLWithDevIDAndKey($healthcheckurl, $developerId, $key);
drawResponse($signedUrl);
?>
<h1>Near Me</h1>
<? 
$signedUrl = generateURLWithDevIDAndKey($nearmeurl, $developerId, $key);
drawResponse($signedUrl);
?>
<h1>Stops for Line</h1>
<? 
$signedUrl = generateURLWithDevIDAndKey($stopsurl, $developerId, $key);
drawResponse($signedUrl);
?>
<h1>General Next Departures</h1>
<? 
$signedUrl = generateURLWithDevIDAndKey($generalurl, $developerId, $key);
drawResponse($signedUrl);
?>
<h1>Specific Next Departures</h1>
<? 
$signedUrl = generateURLWithDevIDAndKey($specificurl, $developerId, $key);
drawResponse($signedUrl);

function generateURLWithDevIDAndKey($apiEndpoint, $developerId, $key)
{
	// append developer ID to API endpoint URL
	if (strpos($apiEndpoint, '?') > 0)
	{
		$apiEndpoint .= "&";
	}
	else
	{
		$apiEndpoint .= "?";
	}
	$apiEndpoint .= "devid=" . $developerId;
 
	// hash the endpoint URL
	$signature = strtoupper(hash_hmac("sha1", $apiEndpoint, $key, false));
 
	// add API endpoint, base URL and signature together
	return "http://timetableapi.ptv.vic.gov.au" . $apiEndpoint . "&signature=" . $signature;
}

function drawResponse($signedUrl)
{
    echo "<p>$signedUrl</p>";
    echo "<textarea rows=\"10\" cols=\"60\">";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $signedUrl); 
    curl_setopt($ch, CURLOPT_TIMEOUT, '3'); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    echo $xmlstr = curl_exec($ch); 
    curl_close($ch);
    
    echo "</textarea>";
}
?>