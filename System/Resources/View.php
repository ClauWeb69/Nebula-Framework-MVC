<?php
class View
{
    protected $viewName;
    protected $srcDir = "../Views/";
    protected $data = [];
    protected $html = false;

    public function __construct($viewName)
    {
        $this->viewName = realpath($this->srcDir . $viewName.'.php');
        if (!file_exists($this->viewName)) {
            throw new Exception("File di vista non trovato: ". $this->viewName . PHP_EOL);
        }
    }

    
    public function __destruct()
    {
        if($this->html)
            return;

        extract($this->data);
        //ob_start();
        require_once($this->viewName);
        //$content = ob_get_contents();
    }


    public function data($data = array())
    {
        $this->data = $data;
        return $this;
        
    }
    public function html($data = array())
    {
        $this->html = true;
        
        extract($this->data);
        ob_start();
        require_once($this->viewName);
        $content = ob_get_clean();

        return $content;
    }
}