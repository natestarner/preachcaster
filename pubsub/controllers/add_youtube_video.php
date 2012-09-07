<?php
function add_youtube_video(){
	global $config;
	require_once $config["path_to_default_site_module"] . "/models/db.php";
	require_once $config["path_to_default_site_module"] . "/models/video.php";
	$clientLibraryPath = $config["path_to_zend_gdata_library"];
	$oldPath = set_include_path(get_include_path() . PATH_SEPARATOR . $clientLibraryPath);
	require_once 'Zend/Loader.php';
	Zend_Loader::loadClass('Zend_Gdata');
	Zend_Loader::loadClass('Zend_Gdata_YouTube');
	$yt = new Zend_Gdata_YouTube();
	$query = $yt->newVideoQuery();
	$query->category = 'Comedy/dog';
	 
	echo $query->queryUrl . "\n";
	$videoFeed = $yt->getVideoFeed($query);
	
	$db_conn = new db($config["db_connection"]);
	$db_resource = $db_conn->get_resource();
	$video = new Video($db_resource);
	
	foreach ($videoFeed as $videoEntry) {
		$pdo_params = array(
			
		);
	    echo "---------VIDEO----------\n";
	    echo "Title: " . $videoEntry->getVideoTitle() . "\n";
	    echo "\nDescription:\n";
	    echo $videoEntry->getVideoDescription();
	    echo "\n\n\n";
	}
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
