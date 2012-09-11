<?php
function youtube_oauth(){
	global $config;
	$app = Slim::getInstance();
	$code = $app->request()->get("code");
	if($code){
		//initial 
		$access_params = array(
			"code" => $code
			,"client_id" => $config["youtube_client_id"]
			,"client_secret" => $config["youtube_client_secret"]
			,"redirect_uri" => $config["youtube_oauth_callback"]
			,"grant_type" => "authorization_code"
		);
		$ch = curl_init("https://accounts.google.com/o/oauth2/token");
		curl_setopt($ch,CURLOPT_POST, count($access_params));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $access_params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$json_string_data = curl_exec($ch);
		curl_close($ch);
		if($json_string_data){
			$auth_data = json_decode($json_string_data,true);
			$_SESSION["youtube_auth"] = $auth_data;
			dump($auth_data);
			//make a call to get the author data
			//log everything in the database
		}else{
			$app->redirect('/');
		}
	}else{
		$app->redirect('/');
	}
}
?>