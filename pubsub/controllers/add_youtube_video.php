<?php
function add_youtube_video(){
	global $config;
	require_once $config["path_to_default_site_module"] . "/models/db.php";
	require_once $config["path_to_default_site_module"] . "/models/video.php";
	
	$ch = curl_init("https://gdata.youtube.com/feeds/api/videos/-/preachcaster?alt=json");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$json_string_data = curl_exec($ch);
	curl_close($ch);
	$video_data = json_decode($json_string_data,true);

	$db_conn = new db($config["db_connection"]);
	$db_resource = $db_conn->get_resource();
	$video = new Video($db_resource);
	
	foreach($video_data["feed"]["entry"] as $single_video){
		//parse out ID
		$tmp_array = explode("/",$single_video["id"]['$t']);
		$video_id = $tmp_array[count($tmp_array) - 1];
		//rxBS1E0KZQU
		//CQzUsTFqtW0
		$video_params = array(
			"video_id" => $video_id
			,"author" => $single_video["author"][0]["name"]['$t']
			,"title" => $single_video["title"]['$t']
			,"description" => $single_video["content"]['$t']
			,"date_recorded" => isset($single_video['yt$recorded']) ? $single_video['yt$recorded']['$t'] : null
			,"location" => isset($single_video['yt$location']) ? $single_video['yt$location']['$t'] : null
			,"url" => isset($single_video['media$group']['media$player']) ? $single_video['media$group']['media$player'][0]["url"] : null
			,"duration" => isset($single_video['media$group']['yt$duration']) ? $single_video['media$group']['yt$duration']["seconds"] : null
			,"comment_url" => isset($single_video['gd$comments']) && isset($single_video['gd$comments']['gd$feedLink']) ? $single_video['gd$comments']['gd$feedLink']["href"] : null
			,"thumbnails" => isset($single_video['media$group']) && isset($single_video['media$group']['media$thumbnail']) ? $single_video['media$group']['media$thumbnail'] : null
		);
		$video->add_video($video_params,"YouTube");
	}
	
	die("here");
	
}
/*
	old zend crap (that works)
 * 
	$clientLibraryPath = $config["path_to_zend_gdata_library"];
	$oldPath = set_include_path(get_include_path() . PATH_SEPARATOR . $clientLibraryPath);
	require_once 'Zend/Loader.php';
	Zend_Loader::loadClass('Zend_Gdata');
	Zend_Loader::loadClass('Zend_Gdata_YouTube');
	$yt = new Zend_Gdata_YouTube();
	$query = $yt->newVideoQuery();
	$query->category = 'dog';
	 
	echo $query->queryUrl . "\n";
	$videoFeed = $yt->getVideoFeed($query);
	
	$db_conn = new db($config["db_connection"]);
	$db_resource = $db_conn->get_resource();
	$video = new Video($db_resource);
	
	foreach ($videoFeed as $videoEntry) {
		$author_data = $videoEntry->getAuthor();
		$author_name = $author_data[0]->getName()->getText();
		$video_data = array(
			"video_id" => $videoEntry->getVideoId()
			,"author" => $author_name
			,"title" => $videoEntry->getVideoTitle()
			,"description" => $videoEntry->getVideoDescription()
			,"date_recorded" => $videoEntry->getVideoRecorded()
			,"location" => $videoEntry->getLocation()
			,"url" => $videoEntry->getVideoWatchPageUrl()
			,"duration" => $videoEntry->getVideoDuration()
			,"private" => $videoEntry->isVideoPrivate() ? 1 : 0
			//,"tags" => $videoEntry->getVideoTags()
			,"category" => $videoEntry->getVideoCategory()
			//,"developer_tags" => $videoEntry->getVideoDeveloperTags()
			,"state" => $videoEntry->getVideoState()
			,"comment_url" => $videoEntry->getVideoCommentFeedUrl()
			,"embeddable" => $videoEntry->getNoEmbed() ? false : true
			//,"racy" => $videoEntry->getRacy()
		);
		$video->add_video($video_data,"YouTube");
	}
 */