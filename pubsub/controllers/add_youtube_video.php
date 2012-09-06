<?php
error_reporting(E_ALL);
function add_youtube_video(){
	global $config;
	include $config["path_to_zend_gdata_loader"];
	/*
	$ch = curl_init("https://gdata.youtube.com/feeds/api/videos/-/Comedy");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$data = curl_exec($ch);
	curl_close($ch);
	 */
	//$xml = file_get_contents("https://gdata.youtube.com/feeds/api/videos/-/Comedy");
	die("here");
	
}
