<?php

function curl_get($url) {   
    $defaults = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 4
    );
   
    $ch = curl_init();
    curl_setopt_array($ch, $defaults);
    if( ! $result = curl_exec($ch))
    {
        error_log(curl_error($ch));
    }
    curl_close($ch);
    return $result;
} 

function parseXML($xml) {
    $video_urls = array();
    $xmlObj = null;
    if (is_string($xml)) {
	$xmlObj = simplexml_load_string($xml);
    } else if ($xml instanceOf SimpleXMLElement) {
	$xmlObj = $xml;
    }
    if ($xmlObj) {
	foreach($xmlObj->group as $group) {
	    if (isset($group->video_url)) {
		$video_urls[] = (string) $group->video_url;
	    }
	}
    }
    return $video_urls;
}
// ============= main  ==============
$filePath = $argv[1];
$categories = array();
if (file_exists($filePath)) {
    $file = simplexml_load_file($filePath);

    $categories = parseXML($file);
    $count = 0;
    foreach($categories as $category) {
	$cnt = curl_get($category);
	$provider_urls = parseXML($cnt);
	var_dump($provider_urls);
	var_dump("===================");
	if ($count++ == 2) {
	    exit(0);
	}
    }
}
?>