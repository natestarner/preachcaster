<?php
class Video{
	private $db;

    public function __construct($db_connection=false){
		if($db_connection && is_object($db_connection)){
			$this->db = $db_connection;
		}
    }
	
	public function add_video($video_data=array(),$site=false){
		if(!$site){
			throw new Exception('You must supply a site.');
		}
		$statement = $this->db->prepare("
			SELECT site_id
			FROM site
			WHERE name = :name");
		$statement->bindValue(":name", $site, PDO::PARAM_STR);
  		$statement->execute();
  		$site_data = $statement->fetch(PDO::FETCH_ASSOC);
		if(!$site_data){
			throw new Exception('Site not found.');
		}
		$pdo_params = array(
			(isset($video_data["video_id"])) ? $video_data["video_id"] : null
			,(isset($video_data["author"])) ? $video_data["author"] : null
			,(isset($video_data["title"])) ? $video_data["title"] : null
			,(isset($video_data["description"])) ? $video_data["description"] : null
			,(isset($video_data["date_recorded"])) ? $video_data["date_recorded"] : null
			,(isset($video_data["location"])) ? $video_data["location"] : null
			,(isset($video_data["url"])) ? $video_data["url"] : null
			,(isset($video_data["duration"])) ? $video_data["duration"] : null
			,(isset($video_data["comment_url"])) ? $video_data["comment_url"] : null
			,$site_data["site_id"]
		);
		$statement = $this->db->prepare("
			INSERT IGNORE INTO video
			(video_id, author, title, description, date_recorded, location, url, duration,comment_url, site_id, date_added)
			VALUES
			(?,?,?,?,?,?,?,?,?,?,NOW())");
  		$statement->execute($pdo_params);
		
		if(isset($video_data["thumbnails"]) && $video_data["thumbnails"]){
			foreach($video_data["thumbnails"] as $single_thumbnail){
				$thumbnail_params = array(
					isset($single_thumbnail["url"]) ? $single_thumbnail["url"] : null 
					,isset($single_thumbnail["height"]) ? $single_thumbnail["height"] : null
					,isset($single_thumbnail["width"]) ? $single_thumbnail["width"] : null
					,isset($single_thumbnail["time"]) ? $single_thumbnail["time"] : null
					,isset($video_data["video_id"]) ? $video_data["video_id"] : null
				);
				$statement = $this->db->prepare("
					INSERT IGNORE INTO thumbnail
					(url, height, width, time, video_id)
					VALUES
					(?,?,?,?,?)");
		  		$statement->execute($thumbnail_params);
			}
		}
	}
}
?>