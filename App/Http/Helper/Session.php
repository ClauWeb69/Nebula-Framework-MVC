<?php
namespace Helper;
class Session {
    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;
  
    public static function init() {
        if (!self::$sessionStarted) {
            self::settings();
            session_start();
            self::$sessionStarted = true;
        }
        if(!isset($_SESSION["SECURITY"]["LASTUPDATE"]))
            $_SESSION["SECURITY"]["LASTUPDATE"] = time();
        if(($_SESSION["SECURITY"]["LASTUPDATE"] + 172800) < time())
            self::regenerate(true);
    }
  
    protected static function settings(){
        ini_set('session.gc_maxlifetime', 172800);
		session_set_cookie_params([
			'lifetime' => 172800,
			'path' => '/',
			'domain' => $_SERVER['HTTP_HOST'],
			'secure' => true,
			'httponly' => true,
			'samesite' => "Strict"
		]);
    }
    public static function set($name, $value) {
        $_SESSION[$name] = $value;
    }
  
    public static function get($name, $default = false) {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
    
        return $default;
    }
  
    public static function remove($name) {
        unset($_SESSION[$name]);
    }
  
    public static function clear() {
        $_SESSION = array();
    }
  
    public static function destroy(){
        self::clear();
        self::regenerate(true);
        session_destroy();
    }
    public static function regenerate($destroy = true) {
        session_regenerate_id($destroy);
    }
}
Session::init();
?>