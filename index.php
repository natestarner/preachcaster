<?php
	session_start();
	require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
	require_once $config["path_to_slim"];
	require_once $config["path_to_twigview"];
	require_once $_SERVER["DOCUMENT_ROOT"] . "/global_functions.php";
	
	$current_module_location = dirname($_SERVER["DOCUMENT_ROOT"].$_SERVER["PHP_SELF"]);
	if($current_module_location == $_SERVER["DOCUMENT_ROOT"]){ //root level
		$current_module_location = $config["path_to_default_site_module"];
	}
	require_once $current_module_location . "/config.php";
	
	//include all of the controller functions
	include_all_files_in_directory($current_module_location . '/controllers');

	//USE $config IN YOUR ROUTES!! Do not use module_config
	$config = array_merge($config, $module_config);
	$config["active_module"] = basename($current_module_location);
	
	
	// Prepare app
	$app = new Slim(array(
	    'view' => 'TwigView'
	    ,'templates.path' => $current_module_location . "/templates"
	)); 
	$twig = $app->view()->getEnvironment();

	$template_location_directories = array(
		$current_module_location . "/templates"
		,$config["path_to_default_site_module"] . "/templates"
		,$_SERVER["DOCUMENT_ROOT"]);
		
	$loader = new Twig_Loader_Filesystem($template_location_directories);
	$twig->setLoader($loader); 
	foreach($config as $var_name => $var_value){
		$twig->addGlobal($var_name, $var_value);
	}
	
	$twig->addGlobal("session",$_SESSION);
	
	//create an array of all the modules
	$modules_list_array = array();
	if ($handle = opendir($_SERVER["DOCUMENT_ROOT"])) {
	    while (false !== ($entry = readdir($handle))) {
	    	$module_config = false;
	        if ($entry != "." && $entry != ".." && is_dir($_SERVER["DOCUMENT_ROOT"] . "/" . $entry)) {
	            if(is_file($_SERVER["DOCUMENT_ROOT"] . "/" . $entry . "/config.php")){
	            	require $_SERVER["DOCUMENT_ROOT"] . "/" . $entry . "/config.php";
					$module_config["handle"] = $entry;
					$modules_list_array[] = array_merge($config,$module_config);
	            }
	        }
	    }
	    closedir($handle);
	}
	
	$twig->addGlobal("module_list",$modules_list_array);
	
	// Define routes
	require_once $current_module_location . "/routes.php";
	
	// Run app
	$app->run();
?>