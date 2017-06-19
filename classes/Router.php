<?php

class Router
{
    $controller;
    $action;
    $params;

    function parse()
    {
        $data = explode("/", substr($_SERVER["REQUEST_URI"],1), 3);
        
        if (count($data) == 2) {
            $controller = $data[0];
            $action = $data[1];
        } elseif (count($data) == 3) {
            $controller = $data[0];
            $action = $data[1];
            $this->params = $data[2];
        } else {
            $this->controller = "main";
            $this->action = "default";
        }

        if (class_exists($controller) && method_exists($controller, $action)) {
            $this->controller = $controller;
            $this->action = $action;
        } 

    }



}


?>
