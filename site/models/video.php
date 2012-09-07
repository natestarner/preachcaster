<?php
class Video{
	private $db;

    public function __construct($db_connection=false){
		if($db_connection && is_object($db_connection)){
			$this->db = $db_connection;
		}
    }
}
?>