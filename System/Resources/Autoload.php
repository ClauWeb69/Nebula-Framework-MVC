<?php
spl_autoload_register(function($class_name){
	$class_name = strtolower($class_name);
    $path = "..".DIRECTORY_SEPARATOR. "App" .DIRECTORY_SEPARATOR."Http".DIRECTORY_SEPARATOR."{$class_name}.php";

    if (file_exists($path)) {
        require_once realpath($path);
    } else if (strpos($class_name, '*') !== false) {
        $class_name = str_replace("*", "", $class_name);
		$class_directory = "..".DIRECTORY_SEPARATOR. "App" .DIRECTORY_SEPARATOR."Http".DIRECTORY_SEPARATOR."{$class_name}".DIRECTORY_SEPARATOR;
        foreach (glob("{$class_directory}*.php") as $filename) {
            require_once realpath($filename);
        }
    }
});
?>