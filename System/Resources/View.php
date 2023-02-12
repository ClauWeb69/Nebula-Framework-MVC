<?php

class View
{
    protected $viewName;
    protected $srcDir = "../Views/";
    protected $data = [];
    protected $html = false;

    protected $view = null;

    public function __construct($viewName)
    {
        $this->viewName = realpath($this->srcDir . $viewName.'.html');
        if (!file_exists($this->viewName)) {
            throw new Exception("File di vista non trovato: ". $this->viewName . PHP_EOL);
        }
        $loader = new \Twig\Loader\FilesystemLoader(dirname($this->viewName));
        $this->view = new \Twig\Environment($loader);
    }

    
    public function __destruct()
    {
        if($this->html)
            return;


        echo $this->view->render(basename($this->viewName), $this->data);
    }


    public function data($data = array())
    {
        $this->data = $data;
        return $this;
        
    }
    public function html()
    {
        $this->html = true;
        return $this->view->render(basename($this->viewName), $this->data);
    }
}