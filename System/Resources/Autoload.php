<?php
function dirCaller($dir = null){
    $backtrace = debug_backtrace();
    $calling_file = $backtrace[1]['file'];
    $calling_dir = dirname($calling_file);
    if($dir){
        if (stripos($calling_dir, $dir) !== false)
            return true;
        
        return false;
    }
    return $calling_dir;
}
function loadControllers ($class_name){
    if(!dirCaller(DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Routes"))
        return;

	$class_name = strtolower($class_name);
    $path = "..".DIRECTORY_SEPARATOR. "App" .DIRECTORY_SEPARATOR. "Controllers" .DIRECTORY_SEPARATOR."{$class_name}.php";

    if (file_exists($path)) {
        require_once realpath($path);
    }
}
function loadHttp ($class_name){
    if(dirCaller(DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Routes"))
        return;

	$class_name = strtolower($class_name);
    $path = "..".DIRECTORY_SEPARATOR. "App" .DIRECTORY_SEPARATOR. "Http" .DIRECTORY_SEPARATOR."{$class_name}.php";

    if (file_exists($path)) {
        require_once realpath($path);
    } else if (strpos($class_name, '*') !== false) {
        $class_name = str_replace("*", "", $class_name);
		$class_directory = "..".DIRECTORY_SEPARATOR. "App" .DIRECTORY_SEPARATOR."Http".DIRECTORY_SEPARATOR."{$class_name}".DIRECTORY_SEPARATOR;
        foreach (glob("{$class_directory}*.php") as $filename) {
            require_once realpath($filename);
        }
    }
}

spl_autoload_register("loadControllers");
spl_autoload_register("loadHttp");
?>