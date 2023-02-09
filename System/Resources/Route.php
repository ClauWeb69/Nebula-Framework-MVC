<?php
class Route {
    private static $routes = []; 
    private static $params = []; 
    private static $group_prefix = ''; 
    private static $accessGroup = true;  
    private $access = true; 

    public function __construct(){
        $this->access = true;
    }
    private function get($route, $func) {
        $this->add("GET", $route, $func);
    }
    private function post($route, $func){
        $this->add("POST", $route, $func);
    }
    private function delete($route, $func){
        $this->add("DELETE", $route, $func);
    }
    private function put($route, $func){
        $this->add( "PUT", $route, $func);
    }
    private function patch($route, $func){
        $this->add("PATCH", $route, $func);
    }
    private function options($route, $func){
        $this->add("OPTIONS", $route, $func);
    }
    private function any($route, $func){
        $this->add($_SERVER['REQUEST_METHOD'], $route, $func);
    }
    private function ajax($route, $func){
        if (strtolower((string) @$_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            $this->add($_SERVER['REQUEST_METHOD'], $route, $func);
    }
    protected function add($method, $route, $func) {
        $route = static::$group_prefix . $route;

        $name = [];
        $argc = 0;
        $mandatory = 0;
        foreach(explode('/', $route) as $index => $arg) {
            switch(@$arg[0]) {
                case '!': ++$argc; ++$mandatory; break;
                case '?': ++$argc; break;
                default: $name[] = $arg; break;
            }
        }
        $name = implode('/', $name);
        static::$routes[strtolower($method)][strtolower($name)] = ["func" => $func, "argc" => $argc, "mandatory" => $mandatory, "access" => $this->access, "accessGroup" => static::$accessGroup];
    }

    protected function match($url, $method) {
        $parts = explode("/", strtolower($url));

        for ($i = count($parts); $i >= 0; $i--) {
            $url = implode("/", array_slice($parts, 0, $i));

            if(isset(static::$routes[strtolower($method)][strtolower($url)])){
                return [
                    "route" => static::$routes[strtolower($method)][strtolower($url)],
                    "name" => strtolower($url)
                ];
            }
        }
        return false;
    }

    protected function args($match, $url){
        $route = $match["route"];
        $name = $match["name"];
        $args = array_filter(explode("/", $this->caseInsensitiveReplace($name, "", $url)));
        if(count($args) >= $route["mandatory"] && count($args) <= $route["argc"]){
            return $args;
        }
        return false;
    }


    protected function caseInsensitiveReplace($search, $replace, $subject) {
        $lowerSubject = strtolower($subject);
        $lowerSearch = strtolower($search);
        $startPos = strpos($lowerSubject, $lowerSearch);
        if ($startPos !== false) {
            $subject = substr_replace($subject, $replace, $startPos, strlen($search));
        }
        return $subject;
    }

    public function group($prefix, $callback) {
        static::$group_prefix = $prefix;
        call_user_func($callback); 
        static::$group_prefix = '';
        static::$accessGroup  = true;
        
	}
    public function accessGroup($callback, $newRoute) {
        if(is_callable($callback)){
            if(!call_user_func($callback)) 
                static::$accessGroup = $newRoute;
        }else{
            if(!$callback)
            static::$accessGroup  = $newRoute;;
        }
        return $this;
    }
    public function access($callback, $newRoute) {
        if(is_callable($callback)){
            if(!call_user_func($callback)) 
                $this->access = $newRoute;
        }else{
            if(!$callback)
                $this->access = $newRoute;;
        }
        return $this;
    }
    public function redirect($b, $second = false){
        if($second)
            header("Refresh: {$second}; url={$b}", true, 303);
        else
		    header("Location: ".$b);
	}

	public function run() {
		$this->require_all("../App/Routes");
        $method = $_SERVER['REQUEST_METHOD'];
		$url = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
		$match = $this->match($url, $method);
        if($match !== false) {

            if($match["route"]["access"] !== true){
                self::redirect($match["route"]["access"]);
                exit;
            }
            if($match["route"]["accessGroup"] !== true){
                self::redirect($match["route"]["accessGroup"]);
                exit;
            }

            $func = $match["route"]["func"];
            if(is_array($func))
                $func = [$func[0], $func[1]];
            if (is_callable($func)) {
                $params = $this->args($match, $url);
                if($params !== false){
                    call_user_func_array($func, $params);
                }else{
                    echo "Errore con i parametri";
                }
            } else {
                echo "L'azione non esiste";
            }
		} else {
			http_response_code(404);
			echo "La rotta non è stata trovata";
		}
	}
	protected function require_all($dir, $depth=0) {
        $scan = glob("$dir/*");
        foreach ($scan as $path) {
            if (preg_match('/\.php$/', $path)) {
                require_once(realpath($path));
            }
            elseif (is_dir($path)) {
                $this->require_all($path, $depth+1);
            }
        }
    }
}
?>