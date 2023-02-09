<?php
    require_once(realpath(dirname(__FILE__)."/Resources/Init.php"));
    require_once(realpath("../vendor/Autoload.php"));
    require_once(realpath(dirname(__FILE__)."/Resources/Config.php"));
    require_once(realpath(dirname(__FILE__)."/Resources/Constants.php"));
    require_once(realpath(dirname(__FILE__)."/Resources/Autoload.php"));
    require_once(realpath(dirname(__FILE__)."/Resources/View.php"));
    require_once(realpath(dirname(__FILE__)."/Resources/Route.php"));
    (new Route())->run();
?>