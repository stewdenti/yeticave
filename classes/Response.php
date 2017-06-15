<?php
/**
 * Created by PhpStorm.
 * User: boss
 * Date: 16.12.2016
 * Time: 19:31
 */

namespace core;

class Response
{

    public function response($r){
        $controller = ControllerFactory::create($r['controller']);
        $action =$r['action'];
        
    
        if ($controller->authorize($action)) {
          if ($r['id']!=""){
            return  $controller->$action($r['id']);
          } else {
            return  $controller->$action();
          }
        } else {
            
          return $controller->forbidden();
        }

        
    }
}