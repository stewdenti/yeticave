<?php

class Router
{
 
    protected static function parse()
    {
        $application_data = [];
        $params = null;

        $data = explode("/", substr($_SERVER["REQUEST_URI"],1), 3);
        
        if (count($data) == 2) {
            $controller = $data[0];
            $action = $data[1];

        } elseif (count($data) == 3) {
            $controller = $data[0];
            $action = $data[1];
            $params = self::parseQuery($data[2]);
        } else {
            $controller = "main";
            $action = "default";
        }

        return array (
            "controller" => ucfirst($controller),
            "action" => $action,
            "params" => $params
                      );
    }

    protected static function parseQuery($qs) 
    {
        $params = [];
        $data = explode("/", $qs);

        for ($i = 0; $i < count($data); $i=+2) {
            if (isset($data[$i]) && isset($data[$i+1])) {
                $params[$data[$i]] = $data[$i+1];    
            }
            
        }

        return $params;
    }

    public static function execute()
    {
        $ex = self::parse();

        if (class_exists($ex["controller"]) && method_exists($ex["controller"], $ex["action"])) {
            $control = new $ex["controller"]($ex["params"]);
            $method = $ex["action"];
            $control->$method();
        }
    }


}


?>
