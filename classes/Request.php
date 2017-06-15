<?php
namespace core;


class Request
{
    private $_default_request_param = "";
    private $_default_controller = "";
    private $_default_action = "";

    public function __construct()
    {
        $c=ConfigManager::get();
        $this->_default_request_param=$c['default_request_param'];
        $this->_default_controller=$c['default_controller'];
        $this->_default_action=$c['default_action'];
    }
    public function parse(){
        $r_method = $this->calcRouteMethod();
        if ($_REQUEST){
            $r_string = $_REQUEST[$this->_default_request_param];
        } else {
            $r_string = NULL;
        }
//        $r_string = $_REQUEST[$this->_default_request_param];


        return array(
            "controller"=>$this->calcController($r_string),
            "action"=>$this->calcAction($r_string,$r_method),
            "id"=>$this->calcID($r_string),
            "route_method"=>$this->calcRouteMethod(),
            "post_param"=>$this->calcPostParams()
        );
    }
    private function calcController($r)
    {
        $res= "controllers\\";
        if ($r){
            $n = explode("/",$r);
            if (strtolower($n[0]) == "authenticate") {
                $res = "core\\";
            }
            $res.=$n[0];
        } else {
           $res.=$this->_default_controller;
        }
        return $res;

    }
    private  function testMethod($class,$method)
    {
        $res=false;
        if (method_exists($obj=new $class(),$method)){
            $res=true;
        }
        return $res;
    }
    private function calcAction($r,$m)
    {
        $action='error';
        if($r) {
            $n = explode("/", $r);
            switch ($m) {
                case "GET":
                    if (isset($n[1]) && $n[1]!=NULL) {
                        if (intval($n[1])) {
                            $action = "view";
                        }
                        if ($n[1] == "new_item") {
                            $action = "new_item";
                        }
                        if ($this->testMethod($this->calcController($r), $n[1])) {
                            $action = $n[1];
                        }
                        if ($n[1]=="index"){
                            $action = "index";
                        }
                    } else {
                        $action="index";
                    }
                    break;
                case "POST":
                    $controoler = $this->calcController($r);
                    if (isset($n[1]) && $n[1]!=NULL)
                    {

                        if (intval($n[1])){
                            $action='update';
                        } elseif ($n[1]=="newitem"){
                            $action="create";
                        }elseif (method_exists(new $controoler, $n[1])) {
                            $action = $n[1];
                        }
                    }
                    break;
                case "DELETE":
                    if (isset($n[1]) && $n[1]!=NULL && intval($n[1])){
                        $action="remove";
                    }
                    break;
            }
        } elseif ($m=="GET"){
            $action = "index";
        }
        return $action;
    }
    private function calcID($r)
    {
        $ID=NULL;
        if ($r){
            $n = explode("/", $r);
            if (isset($n[2]) && $n[2]!=NULL && intval($n[2])){
                $ID=$n[2];
            }
            if (isset($n[1]) && $n[1]!=NULL && intval($n[1])){
                $ID=$n[1];
            }
        }
        return $ID;
    }

    private function calcRouteMethod()
    {
        $res = $_SERVER['REQUEST_METHOD'];
        if (isset($_POST['fake_method'])) {
            $res = $_POST['fake_method'];
        }
        return $res;
    }
    private function calcPostParams()
    {
        return "";
    }


}