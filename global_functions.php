<?php
function dump($data = false,$die = true, $ip_address=false){
	if(!$ip_address || $ip_address == $_SERVER["REMOTE_ADDR"]){
		echo '<pre>';
		var_dump($data);
		echo '</pre>';

		if($die) die();
	}
}
function include_all_files_in_directory($dir){
	if ($handle = opendir($dir)) {
	    while (false !== ($entry = readdir($handle))) {
	        if ($entry != "." && $entry != "..") {
	            include_once $dir . "/" . $entry;
	        }
	    }
	    closedir($handle);
	}
}
?>